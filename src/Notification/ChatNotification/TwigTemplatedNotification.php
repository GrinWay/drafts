<?php

namespace App\Notification\ChatNotification;

use Symfony\Component\Notifier\Message\ChatMessage;
use Symfony\Component\Notifier\Notification\EmailNotificationInterface;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\Recipient\RecipientInterface;

class TwigTemplatedNotification extends Notification implements EmailNotificationInterface
{
	public function __construct(
		private readonly Environment $twig,
		private readonly Environment $twigTemplatePath,
	) {}
}