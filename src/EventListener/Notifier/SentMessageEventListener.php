<?php

namespace App\EventListener\Notifier;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Notifier\Event\SentMessageEvent;

#[AsEventListener]
class SentMessageEventListener
{
    public function __invoke(SentMessageEvent $event)
    {
        $message = $event->getMessage();

        \dump(
            __METHOD__,
            \get_debug_type($message),
            $message,
        );
    }
}
