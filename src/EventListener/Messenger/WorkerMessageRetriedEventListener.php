<?php

namespace App\EventListener\Messenger;

use Symfony\Component\Messenger\Event\WorkerMessageRetriedEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Debug\TraceableEventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

#[AsEventListener(
    event: WorkerMessageRetriedEvent::class,
    method: '__invoke',
)]
class WorkerMessageRetriedEventListener extends AbstractMessengerEventListener
{
    protected function getMessage(): string
    {
        return 'x x x RETRIED x x x';
    }
}
