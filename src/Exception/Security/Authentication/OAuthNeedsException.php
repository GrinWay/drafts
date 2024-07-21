<?php

namespace App\Exception\Security\Authentication;

use Symfony\Component\Security\Core\Exception\AuthenticationException;

class OAuthNeedsException extends AuthenticationException {
	
	/**
	* Message key will be shown to the client
	*/
	public function getMessageKey(): string
    {
        return 'o_auth_needs_exception';
    }
}