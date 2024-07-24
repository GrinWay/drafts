<?php

namespace App\Security\Voter;

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

		if (!$this->container->get('authChecker')->isGranted('IS_AUTHENTICATED_FULLY')) {
			$message = \sprintf(
				'Вы должны подтвердить себя.',
			);
			throw new CustomUserMessageAccountStatusException($message);
		}
		
		if (!$this->container->get('authChecker')->isGranted('ROLE_OWNER')) {
			$message = \sprintf(
				'Недостаточно прав.',
			);
			throw new LackOfPermissionException($message);
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