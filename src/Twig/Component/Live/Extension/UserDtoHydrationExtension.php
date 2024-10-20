<?php

namespace App\Twig\Component\Live\Extension;

use App\Dto\User\UserDto;
use Symfony\UX\LiveComponent\Hydration\HydrationExtensionInterface;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class UserDtoHydrationExtension implements HydrationExtensionInterface
{
	public function __construct(
		private readonly PropertyAccessorInterface $pa,
	) {}
	
	public function supports(string $className): bool
    {
        $isSupported = UserDto::class === $className || \is_subclass_of($className, UserDto::class);
		return $isSupported;
    }

    public function hydrate(mixed $value, string $className): object
    {
        return new UserDto(
			lastName: $this->pa->getValue($value, '[lastName]'),
		);
    }

    public function dehydrate(object $object): array
    {
        return [
			'lastName' => $this->pa->getValue($object, 'lastName'),
        ];
    }
}