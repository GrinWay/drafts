<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;
use App\Service\FragmentUtils;
use Symfony\Component\HttpKernel\Controller\ControllerReference;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpKernel\Fragment\InlineFragmentRenderer;
use Symfony\Bundle\FrameworkBundle\Controller\TemplateController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use App\Entity\User;

class SecurityController extends AbstractController
{
    #[Route(path: '/login/link', name: 'app_login_link', methods: ['GET', 'POST'])]
    public function loginLink(): Response {
        
		
		return new Response('');
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
			'token' => \md5(\rand(0, 100).$user->getUserIdentifier()),
		]);
    }
	
    #[Route(path: '/login', name: 'app_login')]
    public function login(
		AuthenticationUtils $authenticationUtils,
		FragmentUtils $fragmentUtils,
		Request $r,
	): Response {
		
		$targetPath = $fragmentUtils->templateUri('security/success_login.html.twig');
		
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
		//if ($error) \dd($error->getMessageKey(), $error->getMessageData());
		
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            '_target_path' => $targetPath,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout()
    {
		return $this->redirectToRoute('app_home_home');
    }
}
