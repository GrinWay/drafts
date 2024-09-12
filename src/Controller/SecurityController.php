<?php

namespace App\Controller;

use function Symfony\component\string\u;

use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Email\Generator\CodeGeneratorInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Carbon\Carbon;
use App\Form\Type\LoginLinkFormType;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\ExpressionLanguage\Expression;
use App\Service\ConfigService;
use Symfony\Component\Security\Http\Attribute\IsCsrfTokenValid;
use App\Messenger\Command\Message\SecurityAlwaysRememberMe;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Security\Authenticator\FormLoginAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\Error\UserPasswordNotValidFormError;
use App\Type\Note\NoteType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Form\Type\DeleteUserFormType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use App\Repository\UserRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment;
use App\Service\FragmentUtils;
use Symfony\Component\HttpKernel\Controller\ControllerReference;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpKernel\Fragment\InlineFragmentRenderer;
use Symfony\Bundle\FrameworkBundle\Controller\TemplateController;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use App\Entity\User;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

/**
 * @codeCoverageIgnore
 */
class SecurityController extends AbstractController
{
    use TargetPathTrait;

    public function __construct(
        private readonly RequestStack $requestStack,
    ) {
    }

    /**
    * @var Carbon $nowModifiedLoginLinkExpiresIn +10second
    */
    #[Route(path: '/login/link', name: 'app_login_link', methods: ['GET', 'POST'])]
    public function loginLink(
        Carbon $nowModifiedLoginLinkExpiresIn,
        UserRepository $userRepo,
        #[Autowire('@security.authenticator.login_link_signature_hasher.main')]
        $linkHasher,
        Request $request,
    ): Response {
        $userObj = $userRepo->findOneBy(['email' => 's']);
        $user = $request->query->get('user') ?? $userObj->getUserIdentifier();
        if (null === $user) {
            throw new \Exception('Пользователя с email s несуществует');
        }
        $expires = $request->query->get('expires') ?? $nowModifiedLoginLinkExpiresIn->timestamp;
        $hash = $request->query->get('hash') ?? $linkHasher->computeSignatureHash($userObj, $expires);

        if (\is_object($user)) {
            $user = $user->getUserIdentifier();
        }

        $data = [
            'user' => $user,
            'expires' => $expires,
            'hash' => $hash,
        ];
        $form = $this->createForm(LoginLinkFormType::class, $data, options: []);

        return $this->render('security/login_link.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route(path: '/login/json', name: 'app_json_login', methods: ['POST'])]
    public function jsonLogin(
        #[CurrentUser]
        ?User $user,
    ): Response {
        if (null === $user) {
            return $this->json([
                'message' => 'You was not able to authorize',
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $this->json([
            'message' => 'You successfully authorized',
            'user_identifier' => $user->getUserIdentifier(),
            'token' => \md5(\rand(0, 100) . $user->getUserIdentifier()),
        ]);
    }

    #[Route(path: '/login', name: 'app_login')]
    public function login(
        AuthenticationUtils $authenticationUtils,
        FragmentUtils $fragmentUtils,
        SessionInterface $session,
        CsrfTokenManagerInterface $csrfTokenManager,
        $get,
    ): Response {

        $alwaysRememberMe = $get(new SecurityAlwaysRememberMe());

        $targetPath = $this->getTargetPath($session, firewallName: 'main');
        $targetPath ??= $fragmentUtils->templateUri('security/success_login.html.twig');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        //if ($error) \dd($error->getMessageKey(), $error->getMessageData());

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            '_target_path' => $targetPath,
            '_always_remember_me' => $alwaysRememberMe,
        ]);
    }

    #[Route(path: '/login/auto/{email?s}', name: 'app_auto_login')]
    public function loginAuto(
        User $user,
        Security $security,
    ): Response {
        $authenticatorName = FormLoginAuthenticator::class;
        $firewallName = 'main';
        $badges = [
            (new RememberMeBadge())->enable(),
        ];
        $response = $security->login(
            user: $user,
            authenticatorName: $authenticatorName,
            firewallName: $firewallName,
            badges: $badges,
        );

        $response ??= $this->redirectToRoute('app_home_home');

        return $response;
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout()
    {
        throw new \LogicException('You will never come here');
        return $this->redirectToRoute('app_home_home');
    }

    #[Route(path: '/login2fa/email/resend/code', name: 'app_login_2fa_resend_code')]
    public function login2faResend(
        CodeGeneratorInterface $emailCodeGenerator,
        ?User $user,
    ): Response {
        if (null !== $user && $this->isGranted('IS_AUTHENTICATED_2FA_IN_PROGRESS')) {
            $emailCodeGenerator->reSend($user);
        }
        return $this->redirectToRoute('app_home_home');
    }

    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Route(
        path: '/current/user/delete',
        name: 'app_delete_current_user',
        methods: ['GET', 'POST'],
    )]
    public function deleteCurrentUser(
        UserPasswordHasherInterface $hasher,
        EntityManagerInterface $em,
        Security $security,
    ) {
        $form = $this->createForm(DeleteUserFormType::class, options: []);

        $form->handleRequest($this->requestStack->getCurrentRequest());
        $user = $this->getUser();
        $plainPassword = $form->get('password')->getData();
        if ($user && $plainPassword && !$hasher->isPasswordValid($user, $plainPassword)) {
            $message = 'Неправильный пароль.';
            $form->get('password')->addError(new UserPasswordNotValidFormError($message));

            //throw new AccessDeniedException($message);
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $security->logout(false);

            $em->remove($user);
            $em->flush();

            $this->addFlash(
                NoteType::NOTICE,
                \sprintf('Пользователь "%s" был навсегда удалён.', $user->getUserIdentifier())
            );

            return $this->redirectToRoute('app_register');
        }

        return $this->render('security/delete_current_user.html.twig', [
            'form' => $form,
        ]);
    }
}
