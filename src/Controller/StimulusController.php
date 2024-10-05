<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/stimulus', name: 'app_stimulus_')]
class StimulusController extends AbstractController
{
	#[Route('/test', name: 'test')]
    public function test(): Response
    {
		return new Response('
		<div data-controller="lifecycle">NEW CONTROLLER DATA</div>
		');
        return $this->render('stimulus/index.html.twig', [
            'controller_name' => 'StimulusController',
        ]);
    }
}
