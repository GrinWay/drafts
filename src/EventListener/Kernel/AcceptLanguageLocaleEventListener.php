<?php

namespace App\EventListener\Kernel;

use Symfony\Component\PropertyAccess\PropertyAccess;
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
		
		$sessionLocale = $session->get('_locale', null);
		
		if (null !== $sessionLocale) {
			$request->setLocale($sessionLocale);
			return;
		}

		$preferredLocale = $request->getPreferredLanguage(
			$this->appEnabledLocales,
		);
		// maybe someone passed (HTTP_LOCALE -> locale) header
		$locale = $request->headers->get('locale', $preferredLocale);
		
		if (!\in_array($locale, $this->appEnabledLocales)) {
			$locale = $preferredLocale;			
		}
		
		$session->set('_locale', $locale);
		$request->setLocale($locale);			
		
		// Doesn't work here
		//$this->localeSwitcher->setLocale('en');
    }
}
