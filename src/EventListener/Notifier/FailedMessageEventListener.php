<?php

namespace App\EventListener\Notifier;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Notifier\Event\FailedMessageEvent;

#[AsEventListener]
class FailedMessageEventListener
{
    public function __invoke(FailedMessageEvent $event)
    {
        $message = $event->getMessage();
        $error = $event->getError();

        \dump(
            __METHOD__,
            \get_debug_type($message),
            \get_debug_type($error),
            $error->getDebug(),
        );
    }
}
