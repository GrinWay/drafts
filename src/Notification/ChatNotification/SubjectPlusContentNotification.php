<?php

namespace App\Notification\ChatNotification;

use Symfony\Component\Notifier\Message\ChatMessage;
use Symfony\Component\Notifier\Notification\ChatNotificationInterface;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\Recipient\RecipientInterface;

class SubjectPlusContentNotification extends Notification implements ChatNotificationInterface
{
	public function __construct(
		string $subject = '',
		array $channels = [],
		private ?object $optionsObject = null,
	) {
		parent::__construct(
			subject: $subject,
			channels: $channels,
		);
	}
	
    public function asChatMessage(RecipientInterface $recipient, ?string $transport = null): ?ChatMessage
    {
		$clone = clone $this;
		$clone->subject($clone->getSubject() . \PHP_EOL . $clone->getContent());
		
		$chatMessage = ChatMessage::fromNotification($clone);
		
		if (null !== $transport) {
			$chatMessage->transport($transport);
		}
		
		if (null !== $this->optionsObject) {
			$chatMessage->options($this->optionsObject);
		}
		
		return $chatMessage;
    }
}
