<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MessengerController extends AbstractController
{
    #[Route('/messenger', name: 'app_messenger')]
    public function index(): Response
    {
        $params = [
        ];
        return $this->render('messenger/index.html.twig', $params);
    }
}
