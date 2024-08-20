<?php

namespace App\Security\Voter;

use App\Exception\Security\AccessDenied\RoleNotGrantedAccessDeniedException;
use App\Service\AccessDeniedService;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Doctrine\ORM\EntityManagerInterface;
use App\Type\Security\Voter\VoterSubject;
use App\Exception\Security\Authentication\FormLoginNeedsException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\NullToken;
use Psr\Container\ContainerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\CacheableVoterInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use App\Exception\Security\Authentication\LackOfPermissionException;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class IsAuthenticatedVoter extends Voter implements CacheableVoterInterface {
	
	public const ATTRIBUTE = 'VOTE_IS_AUTH';
	
    public function __construct(
		private readonly AuthorizationCheckerInterface $checker,
	) {}
	
    protected function supports(string $attribute, mixed $subject): bool {
		return self::ATTRIBUTE === $attribute;
	}
	
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool {
		if ($this->checker->isGranted('IS_AUTHENTICATED_FULLY') || $this->checker->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
			return true;
		}
		
		return false;
	}
	
	/**
	* CacheableVoterInterface
	*/
	public function supportsAttribute(string $attribute): bool {
		return self::ATTRIBUTE === $attribute;
	}

	/**
	* CacheableVoterInterface
	*/
    public function supportsType(string $subjectType): bool {
		return true;
	}
}