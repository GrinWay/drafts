<?php

namespace App\EventListener\Messenger;

use Symfony\Component\Messenger\Event\WorkerMessageFailedEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Debug\TraceableEventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

#[AsEventListener(
    event: WorkerMessageFailedEvent::class,
    method: '__invoke',
)]
class WorkerMessageFailedEventListener extends AbstractMessengerEventListener
{
    protected function getMessage(): string
    {
        return '- - - FAILED - - -';
    }
}
