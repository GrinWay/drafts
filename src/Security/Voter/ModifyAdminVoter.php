<?php

namespace App\Security\Voter;

use App\Type\Security\Voter\VoterSubject;
use App\Exception\Security\Authentication\FormLoginNeedsException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\NullToken;
use Psr\Container\ContainerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\CacheableVoterInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;

class ModifyAdminVoter extends Voter implements CacheableVoterInterface {
	
	public const EXCEPTION_MESSAGE = 'А у тебя права есть чтобы админов модифицировать?';
	public const ATTRIBUTE = 'MODIFY_ADMIN';
	
    public function __construct(
		private readonly ContainerInterface $container,
	) {}
	
    protected function supports(string $attribute, mixed $subject): bool {
		return self::ATTRIBUTE === $attribute;
	}
	
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool {
		if ($token instanceof NullToken) {
			throw new FormLoginNeedsException(self::EXCEPTION_MESSAGE.' (user is not authorized)');
		}
		
		if (!$this->container->get('authChecker')->isGranted('ROLE_GOD')) {}
		if (!$this->container->get('authChecker')->isGranted('ROLE_OWNER')) {
			return false;
			$message = \sprintf(
				'%s (User with id: "%s")',
				self::EXCEPTION_MESSAGE,
				$token->getUser()->getUserIdentifier(),
			);
			throw new FormLoginNeedsException($message);
		}
		
		return true;
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