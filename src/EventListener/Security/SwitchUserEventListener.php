<?php

namespace App\EventListener\Security;

use Symfony\Component\Security\Http\Event\SwitchUserEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\SwitchUserToken;

#[AsEventListener]
class SwitchUserEventListener {
	public function __construct() {}
	
	public function __invoke(SwitchUserEvent $e): void {
		
		$token = $e->getToken();
		
		if ($token instanceof SwitchUserToken) {
			$realId = 'but you\'re real: "'.$e->getToken()->getOriginalToken()->getUser()->getUserIdentifier();
		} else {
			$realId = 'and that\'s exactly you';
		}
		
		$message = \sprintf(
			'SECURITY: switch to user: "%s" %s"',
			$e->getTargetUser()->getUserIdentifier(),
			$realId,
		);
		
		\dump($message);
		return;
	}
}