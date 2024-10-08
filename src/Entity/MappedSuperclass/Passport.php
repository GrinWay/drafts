<?php

namespace App\Entity\MappedSuperclass;

use App\Repository\PassportRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\MappedSuperclass;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Serializer\Annotation\Groups;

#[MappedSuperclass]
#[ORM\HasLifecycleCallbacks]
abstract class Passport
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected ?int $id = null;

    #[ORM\Column]
    protected ?\DateTimeImmutable $rememberMeUpdatedAt = null;

    public function __construct(
        #[ORM\Column(length: 255)]
        protected ?string $name = null,
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getRememberMeUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->rememberMeUpdatedAt;
    }

    #[ORM\PrePersist]
    public function setRememberMeUpdatedAt(mixed $rememberMeUpdatedAt = null): static
    {
        if (!$rememberMeUpdatedAt instanceof \DateTimeImmutable) {
            $rememberMeUpdatedAt = new \DateTimeImmutable();
        }

        $this->rememberMeUpdatedAt = $rememberMeUpdatedAt;

        return $this;
    }

    public function invalidateRememberMeCookie(): static
    {
        return $this->setRememberMeUpdatedAt();
    }
}
