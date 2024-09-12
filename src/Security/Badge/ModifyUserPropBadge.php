<?php

namespace App\Security\Badge;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\BadgeInterface;

class ModifyUserPropBadge implements BadgeInterface
{
    private readonly \Closure $modifiedPropCallable;

    public function __construct(
        private readonly string $propertyAccessPath,
        callable $modifiedPropCallable,
    ) {
        $this->modifiedPropCallable = \Closure::fromCallable($modifiedPropCallable);
    }

    public function __invoke(?UserInterface $user): void
    {
        if (null === $user) {
            return;
        }

        $pa = PropertyAccess::createPropertyAccessor();

        $data = $pa->getValue($user, $this->propertyAccessPath);

        if (null === $data) {
            return;
        }

        $pa->setValue($user, $this->propertyAccessPath, ($this->modifiedPropCallable)($data));
    }

    /**
    * This budge will be processed after successful authentication
    */
    public function isResolved(): bool
    {
        return true;
    }
}
