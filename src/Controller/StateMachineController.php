<?php

namespace App\Controller;

use Symfony\Component\Workflow\WorkflowInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/state-machine', name: 'app_state_machine_')]
class StateMachineController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(
		//WorkflowInterface $userOrderStateMachine,
	): Response {
        return $this->render('state_machine/index.html.twig', []);
    }
}
