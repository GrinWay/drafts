<?php

namespace App\Notification\ChatNotification;

use Symfony\Component\Notifier\Message\ChatMessage;
use Symfony\Component\Notifier\Notification\ChatNotificationInterface;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\Recipient\RecipientInterface;
use Symfony\Component\Notifier\Message\MessageOptionsInterface;

class ChatterNotification extends Notification implements ChatNotificationInterface
{
	public function __construct(
		string $subject = '',
		array $channels = [],
		private ?MessageOptionsInterface $options = null,
	) {
		parent::__construct(
			subject: $subject,
			channels: $channels,
		);
	}
	
    public function asChatMessage(RecipientInterface $recipient, ?string $transport = null): ?ChatMessage
    {
		$chatMessage = ChatMessage::fromNotification($this);
		
		if (null !== $transport) {
			$chatMessage->transport($transport);
		}
		
		if (null !== $this->options) {
			$chatMessage->options($this->options);
		}
		return $chatMessage;
    }
}
