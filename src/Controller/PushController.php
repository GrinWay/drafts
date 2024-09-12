<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/push', name: 'app_push_')]
class PushController extends AbstractController
{
    #[Route('/displayed/webhook', name: 'displayed_webhook', methods: ['GET', 'POST'])]
    public function displayedWebhook()
    {
        \dump('PUSH: ' . __METHOD__);
        return $this->json([]);
    }

    #[Route('/clicked/webhook', name: 'clicked_webhook', methods: ['GET', 'POST'])]
    public function clickedWebhook()
    {
        \dump('PUSH: ' . __METHOD__);
        return $this->json([]);
    }

    #[Route('/dismissed/webhook', name: 'dismissed_webhook', methods: ['GET', 'POST'])]
    public function dismissedWebhook()
    {
        \dump('PUSH: ' . __METHOD__);
        return $this->json([]);
    }
}
