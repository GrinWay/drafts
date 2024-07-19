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
		$locale = $request->getPreferredLanguage(
			$this->appEnabledLocales,
		);
		$request->setLocale($locale);
		
		// Doesn't work here
		//$this->localeSwitcher->setLocale('en');
    }
}
