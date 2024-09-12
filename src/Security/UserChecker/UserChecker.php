<?php

namespace App\Security\UserChecker;

use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\Validation;
use App\Entity\User;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Exception\Security\UserChecker\UserBannedStatusException;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        if ($user instanceof User) {
            $this->isUserBanned($user);
            return;
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
    }

    private function isUserBanned(User $user): void
    {
        $isBanned = Validation::createIsValidCallable(
            new Constraints\IsTrue(),
        );

        if ($isBanned($user->getPassport()->isBanned())) {
            throw new UserBannedStatusException();
        }
    }
}
