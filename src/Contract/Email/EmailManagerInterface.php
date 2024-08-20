<?php

namespace App\Contract\Email;

interface EmailManagerInterface
{
    public function getFormat(EmailInterface $email): EmailFormatInterface;
}
