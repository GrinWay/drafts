<?php

namespace App\Dto\User;

use Symfony\Component\Validator\Constraints;
use App\Contract\Dto\DtoInterface;
use Symfony\Component\Serializer\Attribute\Groups;

class UserDto implements DtoInterface
{
    public function __construct(
        /**
         * @param $firstName = (['Alex', 'Lilia'])[\rand(0, 1)];
         */
		#[Groups('firstName')]
        #[Constraints\NotBlank(groups: ['strings'], allowNull: true)]
        public ?string $firstName = null,
        #[Constraints\NotBlank(groups: ['strings'], allowNull: true)]
        public ?string $lastName = null,
        #[Constraints\Positive(groups: ['ints'])]
        #[Constraints\MoreThanOrEqual(1, groups: ['ints'])]
        #[Constraints\LessThanOrEqual(200, groups: ['ints'])]
        /**
         * @param $age = \rand(10, 120);
         */
        public ?int $age = null,
    ) {
    }

    public function setAge(?int $age): static
    {
        $this->age = $age;
        return $this;
    }
}
