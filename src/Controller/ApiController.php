<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Bundle\SecurityBundle\Security;
use App\Entity\Media\Image;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Asset\Packages;

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
        private readonly PropertyAccessorInterface $pa,
        private readonly EntityManagerInterface $em,
		private readonly HttpClientInterface $client,
		private readonly Packages $package,
    ) {
    }

    public function test(): JsonResponse
    {
        return $this->json([
            'current_utc_date' => \Carbon\Carbon::now('UTC')->isoFormat('LLLL'),
        ]);
    }

    #[Route('/save/image', methods: ['POST'])]
    public function saveImage(
		Request $request,
		#[Autowire('%app.abs_img_dir%')] string $absImgDir,
		#[Autowire('%app.public_img_dir%')] string $publicImgDir,
	) {
		$payload = $request->getPayload()->all();
		
		$imageFilename = $this->pa->getValue($payload, '[imageFilename]');
		$base64EncodedImage = $this->pa->getValue($payload, '[base64EncodedImage]');
		
		if (null === $imageFilename || null === $base64EncodedImage) {
			return new JsonResponse(status: 400);
		}
		
		$absImgPathname = \sprintf('%s/%s', $absImgDir, $imageFilename);
		$base64EncodedImage = \base64_decode(\preg_replace('~(.*)[,](.+)~', '$2', $base64EncodedImage));
		
		\file_put_contents($absImgPathname, $base64EncodedImage);
		
		// return new AppJsonResponse(...);
		return $this->json([
			'absImgDir' => $absImgDir,
			'publicImgDir' => $this->package->getUrl($publicImgDir),
			'filename' => $imageFilename,
			'absImgPathname' => $absImgPathname,
		]);
	}
	
    #[Route('/save/client/image/{id<[0-9]+>}')]
    public function saveClientImage(
		Request $request,
		?Image $entity,
		#[Autowire('%app.abs_img_dir%')] string $absImgDir,
	): JsonResponse {
		if (null === $entity) {
			return new JsonResponse(status: 400);
		}
		
		$payload = $request->getPayload()->all();
		
        $base64EncodedImage = $this->pa->getValue($payload, '[data]');
        $imageFilename = $this->pa->getValue($payload, '[imageFilename]');
		
		if (null === $base64EncodedImage || null === $imageFilename) {
			return new JsonResponse(status: 422);
		}
		
		$absImgPathname = \sprintf('%s/%s', $absImgDir, $imageFilename);
		
		$base64EncodedImage = \base64_decode(\preg_replace('~(.*)[,](.+)~', '$2', $base64EncodedImage));
		\file_put_contents($absImgPathname, $base64EncodedImage);
		
		$entity->setFilepath($imageFilename);
		
		$this->em->flush();
		
		return $this->json([
			'absImgDir' => $absImgDir,
			'imageFilename' => $imageFilename,
			'absImgPathname' => $absImgPathname,
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
    ): JsonResponse {
		return $this->json([]);
	}
}
