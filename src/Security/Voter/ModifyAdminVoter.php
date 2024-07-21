<?php

namespace App\Security\Voter;

use App\Exception\Security\Authentication\FormLoginNeedsException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\NullToken;
use Psr\Container\ContainerInterface;

class ModifyAdminVoter extends Voter {
	
	public const EXCEPTION_MESSAGE = 'А у тебя права есть чтобы админов модифицировать?';
	
    public function __construct(
		private readonly ContainerInterface $container,
	) {}
	
    protected function supports(string $attribute, mixed $subject): bool {
		return 'VOTE_MODIFY_ADMIN' === $attribute;
	}
	
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool {
		if ($token instanceof NullToken) {
			throw new FormLoginNeedsException(self::EXCEPTION_MESSAGE.' (user is not authorized)');
		}
		
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
}