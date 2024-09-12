<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints;

// Constraints don't work if extend not Entity
#[Constraints\EnableAutoMapping]
class AbstractEntity
{
}
