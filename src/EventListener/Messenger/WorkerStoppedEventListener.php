<?php

namespace App\EventListener\Messenger;

use Symfony\Component\Messenger\Event\WorkerStoppedEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Debug\TraceableEventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

#[AsEventListener(
    event: WorkerStoppedEvent::class,
    method: '__invoke',
)]
class WorkerStoppedEventListener extends AbstractMessengerEventListener
{
    protected function getMessage(): string
    {
        return '__STOP__';
    }
}
