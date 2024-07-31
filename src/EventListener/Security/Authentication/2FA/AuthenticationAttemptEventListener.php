<?php

namespace App\EventListener\Security\Authentication\2FA;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(
	event: 'scheb_two_factor.authentication.attempt',
)]
class AuthenticationAttemptEventListener {
	public function __invoke($event): void {
		\dump(get_debug_type($event));
	}
}