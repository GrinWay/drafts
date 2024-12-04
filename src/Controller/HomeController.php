<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/', name: 'app_')]
class HomeController extends AbstractController
{
    /**
     * @param Request $request
     * @return Response
     */
    #[Route('', name: 'index')]
    public function index(
        Request $request,
    ): Response {
        return $this->render('home/index.html.twig', [
        ]);
    }
}
