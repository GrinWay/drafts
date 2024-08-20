<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\NullToken;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class PublicAccessVoter extends Voter {
	
    public function __construct() {}
	
    protected function supports(string $attribute, mixed $subject): bool {
		return 'VOTE_PUBLIC_ACCESS' === $attribute && $subject instanceof Request;
	}
	
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool {
		return true;
	}
}