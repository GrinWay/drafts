<?php

namespace App\EventListener\Kernel;

use Symfony\Component\Translation\LocaleSwitcher;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Contract\EventListener\KernelEventListenerInterface;

class AcceptLanguageLocaleEventListener implements KernelEventListenerInterface
{
	public function __construct(
		private readonly array $appEnabledLocales,
		//private readonly LocaleSwitcher $localeSwitcher,
	) {}
	
    public function __invoke(
        RequestEvent $event,
    ): void {
		$request = $event->getRequest();
		$session = $request->getSession();
		
		if ($locale = $session->get('_locale', null)) {
			$request->setLocale($locale);
			return;
		}

		$locale = $request->getPreferredLanguage(
			$this->appEnabledLocales,
		);
		$session->set('_locale', $locale);
		$request->setLocale($locale);
		
		// Doesn't work here
		//$this->localeSwitcher->setLocale('en');
    }
}
