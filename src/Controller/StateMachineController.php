<?php

namespace App\Controller;

use Symfony\Component\Notifier\ChatterInterface;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Workflow\WorkflowInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\UserOrder;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;

#[Route('/state-machine', name: 'app_state_machine_')]
class StateMachineController extends AbstractController
{
	public function __construct(
		private readonly EntityManagerInterface $em,
		private readonly UserRepository $userRepo,
	) {}
	
    #[Route('/{id<%app.regexp.digits%>}', name: 'index')]
    public function index(
		WorkflowInterface $userOrderStateMachine,
		?UserOrder $userOrder,
		$allWorkflowsLocator,
		//TelegramService $telegramService,
		?ChatterInterface $chatter,
		Request $request,
	): Response {
		
        return $this->render('state_machine/index.html.twig', [
			
		]);
    }
}
