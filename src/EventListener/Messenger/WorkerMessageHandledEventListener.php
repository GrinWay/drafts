<?php

namespace App\EventListener\Messenger;

use Symfony\Component\Messenger\Event\WorkerMessageHandledEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Debug\TraceableEventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

#[AsEventListener(
    event: WorkerMessageHandledEvent::class,
    method: '__invoke',
)]
class WorkerMessageHandledEventListener extends AbstractMessengerEventListener
{
    protected function getMessage(): string
    {
        return '!HANDLED!';
    }
}
