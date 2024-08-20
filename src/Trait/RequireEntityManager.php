<?php

namespace App\Trait;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Service\Attribute\Required;

trait RequireEntityManager
{
    #[Required]
    public function requireEntityManager(
        EntityManagerInterface $em,
    ): static {
        $emRef = &$this->getEntityManagerRef();

        $clone = clone $this;
        $emRef = $em;
        return $clone;
    }

    abstract function &getEntityManagerRef(): ?EntityManagerInterface;
}
