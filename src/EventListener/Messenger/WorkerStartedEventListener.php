<?php

namespace App\EventListener\Messenger;

use Symfony\Component\Messenger\Event\WorkerStartedEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Debug\TraceableEventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

#[AsEventListener(
    event: WorkerStartedEvent::class,
    method: '__invoke',
)]
class WorkerStartedEventListener extends AbstractMessengerEventListener
{
    protected function getMessage(): string
    {
        return '__START__';
    }
}
