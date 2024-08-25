<?php

namespace App\EventListener\Email;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Mailer\Event\MessageEvent;

#[AsEventListener]
class MessageEventListener
{
	public function __construct(
		#[Autowire('%env(APP_ADMIN_EMAIL)%')]
		private readonly string $adminEmail,
	) {}
	
    public function __invoke(
        MessageEvent $event,
    ): void {
		return;

		$email = $event->getMessage();
		if (!$email instanceof Email) {
			return;
		}
		
		//$event->reject();
	}
}
