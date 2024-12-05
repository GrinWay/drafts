<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/', name: 'app_')]
class HomeController extends AbstractController
{
    /**
     * @param $appDatabaseUrl
     * @param Request $request
     * @return Response
     */
    #[Route('', name: 'home')]
    public function home(
        #[Autowire('%env(APP_DATABASE_URL)%')] $appDatabaseUrl,
        Request $request,
    ): Response {
//        \dump($appDatabaseUrl);

        return $this->render('home/index.html.twig', [
        ]);
    }
}
