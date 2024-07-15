<?php

namespace App\EventSubscriber;

use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class SessionEventSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly ParameterBagInterface $parameters,
    ) {
    }

    public function onKernelRequest(
        $event,
    ): void {
        $r = $event->getRequest();
        if (!$r->hasPreviousSession()) {
            return;
        }
        $s = $r->getSession();

        $this->setLocaleFromSession($r, $s);
    }

    /* EventSubscriberInterface */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => [
                ['onKernelRequest', -1000],
            ],
        ];
    }

    //###> HELPERS ###

    private function setLocaleFromSession(Request $r, Session $s): void
    {
        if ($r->attributes->has('_locale')) {
            $s->set('_locale', $r->getLocale());
        } else {
            $r->setLocale($s->get('_locale', $this->parameters->get('kernel.default_locale')));
        }
    }
}
