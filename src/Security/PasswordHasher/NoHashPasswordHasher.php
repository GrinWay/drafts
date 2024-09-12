<?php

namespace App\Security\PasswordHasher;

use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

class NoHashPasswordHasher implements PasswordHasherInterface, PasswordUpgraderInterface
{
    public function hash(#[\SensitiveParameter] string $plainPassword): string
    {
        if ($this->isPasswordTooLong($plainPassword)) {
            throw new InvalidPasswordException();
        }
        return $plainPassword;
    }

    public function verify(string $hashedPassword, #[\SensitiveParameter] string $plainPassword): bool
    {
        if ($this->isPasswordTooLong($plainPassword)) {
            return false;
        }

        return $hashedPassword === $plainPassword;
    }

    public function needsRehash(string $hashedPassword): bool
    {
        return true; // rehash (call upgradePassword)
        return false;
    }

    /**
    * PasswordUpgraderInterface
    */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        //\dd($newHashedPassword);
        $user->setPassword($newHashedPassword);
    }

    private function isPasswordTooLong($plainPassword): bool
    {
        return self::MAX_PASSWORD_LENGTH < \strlen($plainPassword);
    }
}
