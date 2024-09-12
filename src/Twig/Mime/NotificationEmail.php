<?php

namespace App\Twig\Mime;

use Symfony\Bridge\Twig\Mime\NotificationEmail as SymfonyNotificationEmail;
use Symfony\Component\Mime\Header\Headers;

class NotificationEmail extends SymfonyNotificationEmail
{
    public function getPreparedHeaders(): Headers
    {
        $headers = clone $this->getHeaders();

        if (!$headers->has('From')) {
            if (!$headers->has('Sender')) {
                throw new LogicException('An email must have a "From" or a "Sender" header.');
            }
            $headers->addMailboxListHeader('From', [$headers->get('Sender')->getAddress()]);
        }

        if (!$headers->has('MIME-Version')) {
            $headers->addTextHeader('MIME-Version', '1.0');
        }

        if (!$headers->has('Date')) {
            $headers->addDateHeader('Date', new \DateTimeImmutable());
        }

        // determine the "real" sender
        if (!$headers->has('Sender') && \count($froms = $headers->get('From')->getAddresses()) > 1) {
            $headers->addMailboxHeader('Sender', $froms[0]);
        }

        if (!$headers->has('Message-ID')) {
            $headers->addIdHeader('Message-ID', $this->generateMessageId());
        }

        // remove the Bcc field which should NOT be part of the sent message
        $headers->remove('Bcc');

        return $headers;
    }
}
