<?php

namespace App\Notification\EmailNotification;

use App\Twig\Mime\NotificationEmail;
use Symfony\Bridge\Twig\Mime\NotificationEmail as SymfonyNotificationEmail;
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
        //###> Priority 2 (high)
        private readonly ?string $theme = null,
        //###> Priority 1 (low)
        array $channels = ['email'],
        private readonly ?string $template = null,
        private readonly array $context = [],
        private readonly ?string $actionText = null,
        private readonly ?string $actionUri = null,
        private readonly bool $markdown = true,
    ) {
        parent::__construct(
            subject: $subject,
            channels: $channels,
        );
    }

    public function asEmailMessage(EmailRecipientInterface $recipient, ?string $transport = null): ?EmailMessage
    {
        if (!class_exists(SymfonyNotificationEmail::class)) {
            throw new \LogicException(sprintf('The "%s" method requires "symfony/twig-bridge:>4.4".', __METHOD__));
        }

        $email = NotificationEmail::asPublicEmail()
            ->to($recipient->getEmail())
            ->importance($this->getImportance())
            ->subject($this->getSubject())
            ->content($this->getContent())
            ->context($this->context)
        ;

        if (true === $this->markdown) {
            $email
                ->markdown($this->getContent())
            ;
        }

        if (null !== $this->theme) {
            $email
                ->theme($this->theme)
            ;
        } elseif (null !== $this->template) {
            $email
                ->htmlTemplate($this->template)
            ;
        }

        if (null !== $this->actionText && null !== $this->actionUri) {
            $email
                ->action($this->actionText, $this->actionUri)
            ;
        }

        $emailMessage = new EmailMessage($email);

        return $emailMessage;
    }
}
