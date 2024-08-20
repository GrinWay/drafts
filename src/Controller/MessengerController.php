<?php

namespace App\Controller;

use App\Messenger\Stamp\StopPropagationStamp;
use App\Messenger\Command\Message\HowStampWorks;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Messenger\Stamp\DelayStamp;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * @codeCoverageIgnore
 */
class MessengerController extends AbstractController
{
    #[Route('/messenger', name: 'app_messenger')]
    public function index(
		MessageBusInterface $bus,
		MessageBusInterface $eventBus,
		$get,
	): Response {
		
		$response = $bus->dispatch(
			new HowStampWorks,
			[
				new StopPropagationStamp(),
			]
		);
		
		//\dd($response);
		//\dump($response);
		
        return $this->render('messenger/index.html.twig', [
            'controller_name' => 'MessengerController',
        ]);
    }
}
