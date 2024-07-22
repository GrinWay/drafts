<?php

namespace App\EventListener\Security;

use function Symfony\component\string\u;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Http\Event\SwitchUserEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\SwitchUserToken;
use Symfony\Component\Translation\LocaleSwitcher;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use App\Service\StringService;

#[AsEventListener]
class SwitchUserSetLocaleEventListener {
	public function __construct(
		private readonly array $appEnabledLocales,
	) {}
	
	public function __invoke(SwitchUserEvent $e): void {
		
		$token = $e->getToken();
		
		$user = $token->getUser();

		$request = $e->getRequest();
		
		$lang = $user->getPassport()->getLang();
		
		if (null === $lang) {
			$lang = $request->getPreferredLanguage(
				$this->appEnabledLocales,
			);
		}
		
		if (StringService::isEnabledLocale($lang, $this->appEnabledLocales)) {
			$request->getSession()?->set('_locale', $lang);
		}
	}
}