<?php

namespace App\Entity\Product;

use App\Repository\FoodProductRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\ProductPassport;
use App\Entity\User;

#[ORM\Entity(repositoryClass: FoodProductRepository::class)]
class FoodProduct extends Product
{
    public function __construct(
        ?string $name = null,
        ?string $description = null,
        ?string $price = null,
        bool $isPublic = false,
        ?ProductPassport $passport = null,
        #[ORM\Column]
        private ?\DateTimeImmutable $expiresAt = null,
        ?User $user = null,
    ) {
        parent::__construct(
            name: $name,
            description: $description,
            price: $price,
            isPublic: $isPublic,
            passport: $passport,
            user: $user,
        );
    }

    public function getExpiresAt(): ?\DateTimeImmutable
    {
        return $this->expiresAt;
    }

    public function setExpiresAt(\DateTimeImmutable $expiresAt): static
    {
        $this->expiresAt = $expiresAt;

        return $this;
    }
}
