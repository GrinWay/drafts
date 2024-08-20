<?php

namespace App\Email;

use App\Contract\Email\EmailInterface;
use App\Contract\Email\EmailFormatInterface;
use App\Type\Email\EmailType;

abstract class AbstractEmail implements EmailInterface
{
    protected string $format;

    public function __construct()
    {
        $this->format = EmailType::DEFAULT;
    }


    //###> AUTO CONFIGURATION ###

    public function setFormat(EmailFormatInterface $emailFormat): static
    {

        $this->format = $emailFormat($this);

        return $this;
    }

    //###< AUTO CONFIGURATION ###


    //###> API ###

    public function __invoke(
        string $message = '',
        ...$sprintfArgs,
    ): string {
        return \trim(\sprintf($this->format, \sprintf($message, ...$sprintfArgs)));
    }

    //###< API ###
}
