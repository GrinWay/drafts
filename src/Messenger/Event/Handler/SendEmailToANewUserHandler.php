<?php

namespace App\Messenger\Event\Handler;

use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use App\Messenger\Event\Message\TestUserWasCreated;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class SendEmailToANewUserHandler {
	
	public function __invoke(TestUserWasCreated $message) {
		$response = 'SEND EMAIL TO A NEW USER';
		//throw new UnrecoverableMessageHandlingException();
		\dump($response);
	}
}