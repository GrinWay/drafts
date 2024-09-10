<?php

namespace App\Notification\EmailNotification;

use Symfony\Bridge\Twig\Mime\NotificationEmail;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mailer\Envelope;
use Symfony\Bridge\Twig\Mime\WrappedTemplatedEmail;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\RawMessage;
use Symfony\Component\Mime\Message;
use Symfony\Component\Notifier\Message\ChatMessage;
use Symfony\Component\Notifier\Notification\EmailNotificationInterface;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\Recipient\RecipientInterface;
use Twig\Environment;
use Symfony\Component\Notifier\Message\EmailMessage;
use Symfony\Component\Notifier\Recipient\EmailRecipientInterface;

class TemplatedEmailNotification extends Notification implements EmailNotificationInterface
{
	public function __construct(
		string $subject,
		array $channels,
		private readonly ?string $template = null,
		private readonly array $context = [],
		private readonly ?string $actionName = null,
		private readonly ?string $actionUri = null,
	) {
		parent::__construct(
			subject: $subject,
			channels: $channels,
		);
		
		/*
		if (!$twig->getLoader()->exists($template)) {
			$message = \sprintf('The twig template path: "%s" does not exist', $template);
			throw new \Exception($message);
		}
		*/
	}
	
	public function asEmailMessage(EmailRecipientInterface $recipient, ?string $transport = null): ?EmailMessage
	{
		if (!class_exists(NotificationEmail::class)) {
            throw new \LogicException(sprintf('The "%s" method requires "symfony/twig-bridge:>4.4".', __METHOD__));
        }

        $email = NotificationEmail::asPublicEmail()
            ->to($recipient->getEmail())
            //->subject($this->getSubject())
			->theme('theme')
        ;
		
		if (null !== $this->template) {
			$email
				->htmlTemplate($this->template)
				->context($this->context)
			;
		} else {
			$email
				->markdown($this->getContent())
			;
		}
		
		if (null !== $this->actionName && null !== $this->actionUri) {
			$email
				->action($this->actionName, $this->actionUri)
			;
		}
		
		$emailMessage = new EmailMessage($email);
		
		return $emailMessage;
	}
}