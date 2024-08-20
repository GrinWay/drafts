<?php

namespace App\EventListener\Security\Authentication\Auth2FA;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(
	event: 'scheb_two_factor.authentication.complete',
)]
class Auth2FACompleteEventListener {
	public function __invoke($event): void {
		\dump('scheb_two_factor.authentication.complete');
	}
}