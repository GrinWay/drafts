<?php

namespace App\Security\Authenticator;

use App\Entity\UserPassport;
use App\Entity\User;
use App\Messenger\Command\Message\SecurityAlwaysRememberMe;
use App\Type\Note\NoteType;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\LegacyPasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\CustomCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\CredentialsInterface;
use App\Security\AuthenticationToken\GithubAuthenticationToken;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\PasswordUpgradeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\PreAuthenticatedUserBadge;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;
use App\Security\Badge\ModifyUserPropBadge;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use KnpU\OAuth2ClientBundle\Security\Authenticator\OAuth2Authenticator;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Trait\Security\GitHub\GitHubAccessTokenAware;
use Carbon\Carbon;

class GitHubAuthenticator extends OAuth2Authenticator {
	
	use GitHubAccessTokenAware;
	
	private bool $isNewUser;
	
	public function __construct(
		private readonly ClientRegistry $clientRegistry,
		private readonly EntityManagerInterface $em,
		private readonly UserRepository $userRepo,
		private readonly UrlGeneratorInterface $ug,
		private readonly UserPasswordHasherInterface $userPasswordHasher,
		private $get,
	) {
		$this->isNewUser = false;
	}
	
	public function createToken(Passport $passport, string $firewallName): TokenInterface
    {
        return new GithubAuthenticationToken(
			$passport->getUser(),
			$firewallName,
			$passport->getUser()->getRoles(),
		);
    }
	
	public function supports(Request $request): ?bool
    {
        return 'app_github_login' === $request->attributes->get('_route');
    }
	
	/**
	* AbstractLoginFormAuthenticator
	*/
	protected function getLoginUrl(Request $request): string {
		return $this->ug->generate('app_github_login');
	}
	
    public function authenticate(Request $request): Passport {
		$alwaysRememberMe = ($this->get)(new SecurityAlwaysRememberMe());
		
		$client = $this->clientRegistry->getClient('github_main');
		$accessToken = $this->fetchAccessToken(
			$client,
			options: [
				'expires' => Carbon::now()->add(1000, 'second')->timestamp,
			]
		);
		
		$userBadge = new UserBadge(
			userIdentifier: $accessToken,
			userLoader: function() use ($accessToken, $client, $request) {
				$githubUser = $client->fetchUserFromToken($accessToken);
				$email = $githubUser->getEmail();
				
				$doctrineUser = $this->userRepo->findOneBy([
					'email' => $email,
				]);
				
				if (null === $doctrineUser) {
					$this->isNewUser = true;
					
					$name = $githubUser->getName();
					$lastName = $githubUser->getNickname();
					
					$passport = new UserPassport(
						name: $name,
						lastName: $lastName,
					);
					$password = \rand(100, 1000);
					
					//$this->onTerminateService->add(static fn() => $bus->dispatch(new NewUserCreated()));
					
					$doctrineUser = new User(
						email: $email,
						passport: $passport,
					);
					$hashedPassword = $this->userPasswordHasher->hashPassword(
						$doctrineUser,
						$password,
					);
					$doctrineUser->setPassword($hashedPassword);
					
					$this->em->persist($doctrineUser);
					$this->em->flush();
				}
				
				\dump($githubUser->toArray());
				
				$this->setGitHubAccessToken($request, $accessToken);
				
				return $doctrineUser;
			},
		);
		
		$badges = [
			//new CsrfTokenBadge($csrfTokenId, $csrfToken),
		];
		
		if ($alwaysRememberMe) {
			$badges[] = (new RememberMeBadge)->enable();
		}
		
		$passport = new SelfValidatingPassport(
			userBadge: $userBadge,
			badges: $badges,
		);

		return $passport;
	}
	
	public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response {
		$message = 'Вошёл через GitHub!';
		if (true === $this->isNewUser) {
			$message .= \sprintf(
				' (пользователь "%s" новый, пароль выслан на почту)',
				$token->getUser()->getUserIdentifier(),
			);
		}
		$request->getSession()?->getFlashBag()?->add(NoteType::NOTICE, $message);
		return new RedirectResponse($this->ug->generate('app_home_home'));
	}
	
	public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
		$request->getSession()?->getFlashBag()?->add(NoteType::ERROR, 'Войти через GitHub не получилось');
		return new RedirectResponse($this->ug->generate('app_home_home'));
    }
}