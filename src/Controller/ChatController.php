<?php

namespace App\Controller;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\UX\Turbo\TurboBundle;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\Type as FormType;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Messenger\MessageBusInterface;

class ChatController extends AbstractController
{
    #[Route('/chat', name: 'app_chat')]
    public function index(
		Request $request,
		PropertyAccessorInterface $pa,
		HubInterface $hub,
		#[Autowire('%env(APP_ENV)%')]
		string $appEnv,
		MessageBusInterface $bus,
	): Response {
		$form = $this->createForm(FormType\ChatFormType::class, $model = null, []);
		
		$topicPrefixFromHeader = '';
		
		if (null !== $prefix = $request->query->get('topic-prefix', null)) {
			$topicPrefixFromHeader = $prefix;
		}
		
		$emptyForm = $this->createForm(FormType\ChatFormType::class);
		$messagesFromDatabase = null;
		
		$parameters = [
			'form' => $form,
			'messages' => $messagesFromDatabase,
			'topicPrefixFromHeader' => $topicPrefixFromHeader,
		];
		
		$response = $this->render('chat/index.html.twig', $parameters);
		
		$form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
			
			$message = $pa->getValue($data, '[message]');
			$templateParameters = [
				'message' => $message,
				'empty_form' => $emptyForm,
			];
			
			if (false && null !== $message) {
				// save message to db
				
				if (TurboBundle::STREAM_FORMAT === $request->getPreferredFormat()) {
					$request->setRequestFormat(TurboBundle::STREAM_FORMAT);
					
					return $this->render('chat/new-message.stream.html.twig', $templateParameters);
				}
			}
			
			if (null !== $message) {
				$content = $this->renderView('chat/new-message.stream.html.twig', $templateParameters);
				
				//$hub->publish(new Update(
				try {
					$bus->dispatch(new Update(
						$topic = $topicPrefixFromHeader.'main-live-chat',
						$content,
					));
				} catch (\Exception $e) {
					\dump('ERROR: Dispatching a Mercure Update failed');
				}
			}
			
			/*
			return $this->redirectToRoute(
				$request->attributes->get('_route'),
				$request->attributes->get('_route_params'),
			);
			*/
			$response->setStatusCode(303);
		}
		
        return $response;
    }
}
