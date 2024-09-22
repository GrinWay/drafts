<?php

namespace App\Entity;

use App\Contract\Entity\EntityInterface;
use Symfony\Component\Validator\Constraints;

// Constraints don't work if extend not Entity
#[Constraints\EnableAutoMapping]
class AbstractEntity implements EntityInterface
{
}
