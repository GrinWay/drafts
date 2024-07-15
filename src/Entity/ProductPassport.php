<?php

namespace App\Entity;

use App\Entity\Product\Product;
use App\Repository\ProductPassportRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use GrinWay\WebApp\Trait\Doctrine\UpdatedAt;
use GrinWay\WebApp\Trait\Doctrine\CreatedAt;

#[ORM\Entity(repositoryClass: ProductPassportRepository::class)]
class ProductPassport
{
    use UpdatedAt;
    use CreatedAt;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist'], mappedBy: 'passport', orphanRemoval: true)]
    private ?Product $product = null;

    public function __construct(
        #[ORM\Column(length: 255)]
        private ?string $name = null,
        #[ORM\Column(type: Types::JSON)]
        private array $category = [],
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): static
    {
        $this->product = $product;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCategory(): array
    {
        return $this->category;
    }

    public function setCategory(array $category): static
    {
        $this->category = $category;

        return $this;
    }
}
