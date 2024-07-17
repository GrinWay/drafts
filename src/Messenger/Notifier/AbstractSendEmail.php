<?php

namespace App\Messenger\Notifier;

use App\Messenger\AsyncMessageInterface;
use Symfony\Component\Validator\Constraints;
use GrinWay\WebApp\Contract\Messenger\MessageHasSyncTransportInterface;

abstract class AbstractSendEmail implements AsyncMessageInterface
{
    public function __construct(
        #[Constraints\NotBlank(groups: ['full', 'important'])]
        public readonly string $toEmail,
        #[Constraints\NotBlank(groups: ['full'])]
        public string $title = '',
        public string $body = '',
        public string $bottom = '',
    ) {
    }

    public function setBottom(string $v): static
    {
        $this->bottom = $v;

        return $this;
    }

    public function setBody(string $v): static
    {
        $this->body = $v;

        return $this;
    }

    public function setTitle(string $v): static
    {
        $this->title = $v;

        return $this;
    }

    /* Alias */
    public function getTo(): string
    {
        return $this->getToEmail();
    }

    public function getToEmail(): string
    {
        return $this->toEmail;
    }

    /* Alias */
    public function __invoke(): string
    {
        return $this->__toString();
    }

    public function __toString(): string
    {
        return $this->title . ': "' . $this->body . '"' . \PHP_EOL . $this->bottom;
    }
}
