<?php

namespace App\Exception\Security\Authentication;

use Symfony\Component\Security\Core\Exception\AuthenticationException;

class FormLoginNeedsException extends AuthenticationException
{
    /**
    * Message key will be shown to the client
    */
    public function getMessageKey(): string
    {
        return 'form_login_needs_exception';
    }
}
