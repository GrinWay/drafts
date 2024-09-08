<?php

namespace App\Notification\ChatNotification;

use Symfony\Component\Notifier\Message\ChatMessage;
use Symfony\Component\Notifier\Notification\ChatNotificationInterface;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\Recipient\RecipientInterface;

class SubjectPlusContentNotification extends Notification implements ChatNotificationInterface
{
	public function asChatMessage(RecipientInterface $recipient, ?string $transport = null): ?ChatMessage
	{
		if ('telegram' === $transport) {
			$clone = clone $this;
			$clone->subject($clone->getSubject().\PHP_EOL.$clone->getContent());
			return ChatMessage::fromNotification($clone);
		}
		
		return null;
	}
}