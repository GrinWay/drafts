<?php

namespace App\EventListener\Notifier;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Notifier\Event\MessageEvent;

#[AsEventListener]
class MessageEventListener
{
    public function __invoke(MessageEvent $event)
    {
        $message = $event->getMessage();

        \dump(__METHOD__, \get_debug_type($message));
    }
}
