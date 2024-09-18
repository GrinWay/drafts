<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/sms', name: 'app_sms_')]
class SmsController extends AbstractController
{
	#[Route('/twilio/webhook', methods: ['GET', 'POST'], name: 'twilio_webhook')]
    public function twilioWebhook()
    {
		\dump('SMS: '.__METHOD__);
        return $this->json([]);
    }
}
