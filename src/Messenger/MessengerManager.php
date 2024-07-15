<?php

namespace App\Messenger;

use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use App\Messenger\Query\QueryInterface;
use App\Contract\Messenger\CommandBusHandlerInterface;
use App\Contract\Messenger\EventBusHandlerInterface;

class MessengerManager
{
    public function __construct(
        private $commandBusHandlers,
        private $eventBusHandlers,
    ) {
        $this->commandBusHandlers = \iterator_to_array($commandBusHandlers);
        $this->eventBusHandlers = \iterator_to_array($eventBusHandlers);
    }

    public function get(): string
    {
        return __METHOD__;
    }
}
