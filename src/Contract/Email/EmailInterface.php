<?php

namespace App\Contract\Email;

interface EmailInterface
{
    public function setFormat(EmailFormatInterface $emailFormat): static;
}
