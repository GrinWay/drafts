<?php

namespace GrinWay\GenericParts\EventSubscriber;

use Symfony\Component\HttpKernel\KernelEvents;
use GrinWay\GenericParts\Messenger\KernelBootstrap\Command\{
    InitCarbon,
    SetDefaultDateTimeZone
};
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Carbon\Carbon;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\{
    Response,
    JsonResponse,
    Request
};
use Symfony\Component\HttpKernel\Event\{
    TerminateEvent,
    FinishRequestEvent,
    ControllerArgumentsEvent,
    ResponseEvent,
    ExceptionEvent,
    ViewEvent,
    RequestEvent,
    ControllerEvent
};
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class InitSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private MessageBusInterface $bus,
        private $timezone,
        private $debugLogger,
    ) {
    }

    //###> EVENTS ###
    public function onKernelRequest(RequestEvent $event)
    {
        //$url      = $event->getRequest()->getBaseUrl() . $event->getRequest()->getPathInfo();
        $this->handleRequest($event);
    }
    //###< EVENTS ###


    //###> HELPERS ###
    private function handleRequest($event)
    {
        foreach (
            [
                new SetDefaultDateTimeZone($this->timezone),
                new InitCarbon(),
            ] as $message
        ) {
            $this->bus->dispatch($message);
        }
    }
    //###< HELPERS ###

    /** implements EventSubscriberInterface */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', 256],
        ];
    }
}
