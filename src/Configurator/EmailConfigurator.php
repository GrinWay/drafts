<?php

namespace App\Configurator;

use App\Contract\Email\EmailInterface;
use App\Contract\Email\EmailManagerInterface;

class EmailConfigurator
{
    public function __construct(
        private readonly EmailManagerInterface $emailManager,
    ) {
    }

    public function __invoke(EmailInterface $email): void
    {

        $email->setFormat(
            $this->emailManager->getFormat($email),
        );
    }
}
