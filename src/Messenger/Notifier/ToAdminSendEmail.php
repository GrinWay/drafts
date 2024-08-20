<?php

namespace App\Messenger\Notifier;

use App\Messenger\AsyncMessageHighPriorityInterface;

class ToAdminSendEmail extends AbstractSendEmail implements AsyncMessageHighPriorityInterface
{
    public function __construct(
        string $toEmail,
        string $title = '',
        string $body = '',
        string $bottom = '',
    ) {
        parent::__construct(
            toEmail: $toEmail,
            title: $title,
            body: $body,
            bottom: $bottom,
        );
    }
}
