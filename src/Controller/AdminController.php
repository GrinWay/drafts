<?php

namespace App\Controller;

use App\Type\Security\Voter\VoterSubject;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(
	'MODIFY_ADMIN',
	message: 'Доступ разрешён только для тех, кто может управлять админами.',
	//statusCode: Response::HTTP_UNAUTHORIZED,
	//exceptionCode: 180898,
)]
#[Route(path: '/admin')]
class AdminController extends AbstractController
{
    #[Route(path: '/')]
    public function index(): Response
    {
        return $this->render('@admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
}
