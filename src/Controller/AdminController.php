<?php

namespace App\Controller;

use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\ExpressionLanguage\Expression;
use App\Entity\Product\Product;
use App\Type\Security\Voter\VoterSubject;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;

/**
 * @codeCoverageIgnore
 */
class AdminController extends AbstractController
{
	#[IsGranted(
		'ROLE_OWNER',
		message: 'Доступ разрешён только для тех, кто может управлять админами.',
		//statusCode: Response::HTTP_UNAUTHORIZED,
		//exceptionCode: 180898,
	)]
    #[Route(path: '/admin', defaults: [
	])]
	public function index(): Response {
        return $this->render('@admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
}
