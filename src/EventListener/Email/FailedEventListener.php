<?php

namespace App\EventListener\Email;

use Symfony\Component\Mime\Email;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Mailer\Event\FailedMessageEvent;

#[AsEventListener]
class FailedEventListener
{
    public function __invoke(
        FailedMessageEvent $event,
    ): void {
		\dump($event->getError());
	}
}
