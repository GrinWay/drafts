<?php

namespace App\Controller;

use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Bundle\SecurityBundle\Security;

/**
* @codeCoverageIgnore
*/
#[Route('/api', methods: ['GET', 'POST'])]
//#[IsGranted('VOTE_API_TOKEN')]
//#[IsGranted('IS_AUTHENTICATED')]
class ApiController extends AbstractController
{
	public function __construct(
		private readonly Security $security,
	) {
	}
	
    #[Route('/get')]
    public function index(): JsonResponse
    {
		return $this->json([
			'token_type' => \get_debug_type($this->security->getToken()),
			'user_type' => \get_debug_type($this->security->getToken()?->getUser()),
			'user_id' => $this->security->getToken()?->getUser()?->getUserIdentifier(),
		]);
    }
	
    #[Route('/mock/github/get/repository/{partOfInformation}')]
    public function mockGitHubGetRepository(
		$partOfInformation,
		PropertyAccessorInterface $pa,
	): JsonResponse {
		$data = [
			"login" => "GrinWay",
			"id" => 175572154,
			"node_id" => "U_kgDOCncEug",
			"avatar_url" => "https://avatars.githubusercontent.com/u/175572154?v=4",
			"gravatar_id" => "",
			"url" => "https://api.github.com/users/GrinWay",
			"html_url" => "https://github.com/GrinWay",
			"followers_url" => "https://api.github.com/users/GrinWay/followers",
			"following_url" => "https://api.github.com/users/GrinWay/following{/other_user}",
			"gists_url" => "https://api.github.com/users/GrinWay/gists{/gist_id}",
			"starred_url" => "https://api.github.com/users/GrinWay/starred{/owner}{/repo}",
			"subscriptions_url" => "https://api.github.com/users/GrinWay/subscriptions",
			"organizations_url" => "https://api.github.com/users/GrinWay/orgs",
			"repos_url" => "https://api.github.com/users/GrinWay/repos",
			"events_url" => "https://api.github.com/users/GrinWay/events{/privacy}",
			"received_events_url" => "https://api.github.com/users/GrinWay/received_events",
			"type" => "User",
			"site_admin" => false,
			"name" => "Григорий",
			"company" => null,
			"blog" => "",
			"location" => null,
			"email" => "grin180898@mail.ru",
			"hireable" => null,
			"bio" => "PHP Symfony developer (websites)",
			"twitter_username" => null,
			"public_repos" => 9,
			"public_gists" => 0,
			"followers" => 0,
			"following" => 0,
			"created_at" => "2024-07-15T01:12:07Z",
			"updated_at" => "2024-07-30T21:35:42Z",
			"private_gists" => 0,
			"total_private_repos" => 1,
			"owned_private_repos" => 1,
			"disk_usage" => 37616,
			"collaborators" => 0,
			"two_factor_authentication" => false,
			"plan" =>  [
				"name" => "free",
				"space" => 976562499,
				"collaborators" => 0,
				"private_repos" => 10000,
			],
		];
		
		$data = $pa->getValue($data, '['.$partOfInformation.']');
		
		return $this->json([
			$partOfInformation => $data,
		]);
    }
}
