<?php

namespace App\Sms\Twilio\Webhook;

use Symfony\Component\RemoteEvent\Attribute\AsRemoteEventConsumer;
use Symfony\Component\RemoteEvent\Consumer\ConsumerInterface;
use Symfony\Component\RemoteEvent\Event\Sms\SmsEvent;
use Symfony\Component\RemoteEvent\RemoteEvent;

#[AsRemoteEventConsumer('notifier_twilio')]
class WebhookListener implements ConsumerInterface
{
	public function consume(RemoteEvent $event): void
    {
        if ($event instanceof SmsEvent) {
            $this->handleSmsEvent($event);
        } else {
            // This is not an SMS event
            return;
        }
    }

    private function handleSmsEvent(SmsEvent $event): void
    {
        \dump(\get_debug_type($event), __METHOD__);
    }
}