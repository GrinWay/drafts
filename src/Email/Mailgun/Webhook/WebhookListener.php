<?php

namespace App\Email\Mailgun\Webhook;

use Symfony\Component\RemoteEvent\Attribute\AsRemoteEventConsumer;
use Symfony\Component\RemoteEvent\Consumer\ConsumerInterface;
use Symfony\Component\RemoteEvent\Event\Mailer\MailerDeliveryEvent;
use Symfony\Component\RemoteEvent\Event\Mailer\MailerEngagementEvent;
use Symfony\Component\RemoteEvent\RemoteEvent;

#[AsRemoteEventConsumer('mailer_mailgun')]
class WebhookListener implements ConsumerInterface
{
	public function consume(RemoteEvent $event): void
    {
        if ($event instanceof MailerDeliveryEvent) {
            $this->handleMailDelivery($event);
            return;
        }
		
		if ($event instanceof MailerEngagementEvent) {
            $this->handleMailEngagement($event);
            return;
		}
    }
	
	/**
     * Helper
     */
	private function handleMailDelivery(MailerDeliveryEvent $event): void
    {
		\dump($event->getReason());
		//$this->dump($event, __METHOD__);
    }

	/**
     * Helper
     */
    private function handleMailEngagement(MailerEngagementEvent $event): void
    {
		$this->dump($event, __METHOD__);
    }
	
	/**
     * Helper
     */
	private function dump(RemoteEvent $event, string $functionName): void {
        \dump(
			\get_debug_type($event),
			$functionName,
		);
	}
}