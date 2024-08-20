<?php

namespace App\Contract\Email;

interface EmailFormatInterface
{
    public function __construct(EmailInterface $email);

    public function __invoke(): string;
}
