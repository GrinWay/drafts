<?php

namespace App\EventListener\Security;

use Symfony\Component\Security\Http\Event\TokenDeauthenticatedEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

#[AsEventListener(
	dispatcher: 'security.event_dispatcher.main',
)]
class MainFirewallTokenDeauthenticatedEventListener {
	public function __construct() {}
	
	public function __invoke(TokenDeauthenticatedEvent $e): void {
		\dump('Чувствительные данные пользователя были изменены.');
		return;
	}
}