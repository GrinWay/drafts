<?php

namespace App\EventListener\Messenger;

use Symfony\Component\Messenger\Event\WorkerRateLimitedEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Debug\TraceableEventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

#[AsEventListener(
    event: WorkerRateLimitedEvent::class,
    method: '__invoke',
)]
class WorkerRateLimitedEventListener extends AbstractMessengerEventListener
{
    protected function getMessage(): string
    {
        return 'RATE LIMITER';
    }
}
