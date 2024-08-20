<?php

namespace GrinWay\GenericParts\Messenger;

use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

class SyncMessage
{
    use HandleTrait;

    public function __construct(
        // this bus service for HandleTrait
        MessageBusInterface $messageBus,
    ) {
        $this->messageBus   = $messageBus;
    }

    /*
        Удобный спобоб возвращать результат синхронного Handler
        $result = $<thisService>(new <Message>);
    */
    public function __invoke($syncMessage)
    {
        return $this->handle($syncMessage);
    }
}
