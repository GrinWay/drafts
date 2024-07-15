<?php

namespace App\Email;

use App\Contract\Email\EmailManagerInterface;
use App\Contract\Email\EmailInterface;
use App\Contract\Email\EmailFormatInterface;

class EmailManager implements EmailManagerInterface
{
    public function getFormat(EmailInterface $email): EmailFormatInterface
    {
        return new EmailFormat($email);
    }
}
