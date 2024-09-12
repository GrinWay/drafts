<?php

namespace App\Security\AccessManager;

use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class AccessManager implements AccessDecisionManagerInterface
{
    public function decide(TokenInterface $token, array $attributes, mixed $object = null): bool
    {
        /**
        * You have to call voters by your self
        */
        return false;
    }
}
