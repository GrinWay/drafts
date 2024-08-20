<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\NullToken;
use Psr\Container\ContainerInterface;

class SwitchUserVoter extends Voter {
	
    public function __construct() {}
	
    protected function supports(string $attribute, mixed $subject): bool {
		return 'VOTE_SWITCH_USER' === $attribute;
	}
	
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool {
		if ($token instanceof NullToken) {
			return false;
		}
		
		if (!$token->getUser()->isSwitchUserAble()) {
			return false;
		}
		
		return true;
	}
}