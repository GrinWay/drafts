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

class ModifyAdminVoter extends Voter implements CacheableVoterInterface
{
    public const EXCEPTION_MESSAGE = 'А у тебя права есть чтобы админов модифицировать?';
    public const ATTRIBUTE = 'MODIFY_ADMIN';

    public function __construct(
        private readonly ContainerInterface $container,
    ) {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return self::ATTRIBUTE === $attribute;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {

        $this->container->get('accessService')->denyAccessUnlessGranted(
            'IS_AUTHENTICATED_FULLY',
            onRoleNotGranted: static fn($role) => throw new FormLoginNeedsException(),
        );

        $this->container->get('accessService')->denyAccessUnlessGranted(
            $role = 'ROLE_OWNER',
            onRoleNotGranted: static fn($role) => throw new RoleNotGrantedAccessDeniedException($role),
        );

        return true;
    }

    /**
    * CacheableVoterInterface
    */
    public function supportsAttribute(string $attribute): bool
    {
        return self::ATTRIBUTE === $attribute;
    }

    /**
    * CacheableVoterInterface
    */
    public function supportsType(string $subjectType): bool
    {
        return true;
    }
}
