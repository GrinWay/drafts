<?php

namespace App\EventListener\Security;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Http\Event\SwitchUserEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\SwitchUserToken;
use Symfony\Component\Translation\LocaleSwitcher;

#[AsEventListener]
class SwitchUserSetLocaleEventListener {
	public function __construct(
		private readonly RequestStack $requestStack,
	) {}
	
	public function __invoke(SwitchUserEvent $e): void {
		
		$token = $e->getToken();
		
		$user = $token->getUser();

		$lang = $user->getPassport()->getLang();
		
		$this->requestStack->getCurrentRequest()?->getSession()?->set('_locale', $lang);
	}
}