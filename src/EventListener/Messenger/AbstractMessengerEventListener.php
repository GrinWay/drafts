<?php

namespace App\EventListener\Messenger;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

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
			if (!\method_exists($event, 'getReceiverName')) {
				$dop .= ' transport(sync|in-memory)';
			}
        }
		if (\method_exists($event, 'getReceiverName')) {
            $dop .= ' transport(' . $event->getReceiverName() . ')';
		}

        $mess = self::PREFIX . ': "' . $this->getMessage() . '"' . $dop;

        \dump($mess);
    }

    abstract protected function getMessage(): string;
}
