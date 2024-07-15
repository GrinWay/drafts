<?php

namespace App\EventListener\Messenger;

use Symfony\Component\Messenger\Event\SendMessageToTransportsEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Debug\TraceableEventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

#[AsEventListener(
    event: SendMessageToTransportsEvent::class,
    method: '__invoke',
)]
class SendMessageToTransportsEventListener extends AbstractMessengerEventListener
{
    protected function getMessage(): string
    {
        return '(DISPATCH MESSAGE) -(SEND)-> (TRANSPORT)';
    }
}
