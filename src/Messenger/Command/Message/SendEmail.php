<?php

namespace App\Messenger\Command\Message;

class SendEmail
{
    public function __construct(
        public readonly string $email,
        public readonly string $message,
    ) {
    }

    public function __invoke(): string
    {
        return \sprintf(
            'Message to email: "%s", with content: "%s"',
            $this->email,
            $this->message,
        );
    }
}
