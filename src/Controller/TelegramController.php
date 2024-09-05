<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/telegram', name: 'app_telegram_')]
class TelegramController extends AbstractController
{
    #[Route('/update', name: 'update')]
    public function index(): Response
    {
        return $this->render('telegram/index.html.twig', [
            'controller_name' => 'TelegramController',
        ]);
    }
}
