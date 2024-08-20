<?php

namespace App\EventSubscriber\Kernel;

use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionEventSubscriber implements EventSubscriberInterface
{
    /**
    * EventSubscriberInterface
    */
    public static function getSubscribedEvents()
    {
        return [];
        return [
            KernelEvents::EXCEPTION => [
                [
                    'onKernelException',
                    10,
                ],
            ],
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        \dd('SUBSCRIBER');
    }
}
