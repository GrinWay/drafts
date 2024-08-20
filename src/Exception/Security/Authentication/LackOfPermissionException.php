<?php

namespace App\Exception\Security\Authentication;

use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;

class LackOfPermissionException extends CustomUserMessageAccountStatusException {
	/**
	* Message key will be shown to the client
	*/
	public function getMessageKey(): string
    {
        return 'lack_of_permission';
    }
}