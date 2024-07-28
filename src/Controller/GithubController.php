<?php

namespace App\Controller;

use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Carbon\Carbon;

#[Route('/github')]
class GithubController extends AbstractController
{
	public function __construct(
		private readonly ClientRegistry $clientRegistry,
	) {
	}
	
	#[Route('/', name: 'app_github_index')]
    public function index() {
		return $this->clientRegistry->getClient('github_main')
			->redirect(
				scopes: [
					'read:packages',
					'read:project',
				],
				options: [
					
				],
			)
		;
    }
	
	#[Route('/login', name: 'app_github_login')]
    public function login(
		Request $request,
	) {
		$client = $this->clientRegistry->getClient('github_main');
		$user = $client->fetchUser();
		
		return $this->redirectToRoute('app_home_home');
    }
	
	#[Route('/app', name: 'app_github_app')]
    public function app(
		Request $request,
	) {
		$client = $this->clientRegistry->getClient('github_main');
		$user = $client->fetchUser();
		\dump(__METHOD__);
		return $this->redirectToRoute('app_home_home');
    }
}
