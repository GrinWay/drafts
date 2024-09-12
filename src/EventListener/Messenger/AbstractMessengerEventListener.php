<?php

namespace App\EventListener\Messenger;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Messenger\Event\SendMessageToTransportsEvent;

abstract class AbstractMessengerEventListener
{
    public const PREFIX = 'MESSENGER';

    public function __invoke(
        $event,
        string $eventClassName,
        EventDispatcherInterface $dispatcher,
    ) {
        $dop = '';

        if (\method_exists($event, 'getEnvelope')) {
            $ref = new \ReflectionClass($event->getEnvelope()->getMessage());
            $dop .= ' message(' . $ref->getShortName() . ')';
        }
        if (\method_exists($event, 'getReceiverName')) {
            $dop .= ' transport("' . $event->getReceiverName() . '")';
        } elseif (\method_exists($event, 'getSenders')) {
            $dop .= ' transport("' . \implode('", "', \array_keys($event->getSenders())) . '")';
        }

        $mess = self::PREFIX . ': "' . $this->getMessage() . '"' . $dop;

        \dump($mess);
    }

    abstract protected function getMessage(): string;
}
