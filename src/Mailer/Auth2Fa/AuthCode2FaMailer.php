<?php

namespace App\Mailer\Auth2Fa;

use Scheb\TwoFactorBundle\Model\Email\TwoFactorInterface;
use Scheb\TwoFactorBundle\Mailer\AuthCodeMailerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class AuthCode2FaMailer implements AuthCodeMailerInterface
{
    public function __construct(
        private readonly MailerInterface $mailer,
    ) {
    }

    public function sendAuthCode(TwoFactorInterface $user): void
    {
        $authCode = $user->getEmailAuthCode();

        $email = (new TemplatedEmail())
            ->htmlTemplate('2fa/auth/email/email.html.twig')
            ->context([
                'code' => $authCode,
            ])
        ;

        $this->mailer->send($email);
    }
}
