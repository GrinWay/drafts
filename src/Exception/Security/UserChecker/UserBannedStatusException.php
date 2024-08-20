<?php

namespace App\Exception\Security\UserChecker;

use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;

class UserBannedStatusException extends CustomUserMessageAccountStatusException {
	public function getMessageKey(): string
    {
        return 'app.user.you_banned';
    }
}