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

    public function test(): JsonResponse
    {
        return $this->json([
            'current_utc_date' => \Carbon\Carbon::now('UTC')->isoFormat('LLLL'),
        ]);
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
		return $this->json([]);
	}
}
