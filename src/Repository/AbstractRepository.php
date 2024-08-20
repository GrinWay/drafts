<?php

namespace App\Repository;

use App\Entity\AppEntityProductTypeFoodProductType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

abstract class AbstractRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, string $class)
    {
        parent::__construct($registry, $class);
    }
}
