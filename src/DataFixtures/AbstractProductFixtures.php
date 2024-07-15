<?php

namespace App\DataFixtures;

use App\Entity\User;
use Faker\Generator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

abstract class AbstractProductFixtures extends AbstractFixtures
{
    public function __construct(
        Generator $faker,
    ) {
        parent::__construct(
            faker: $faker,
        );
    }

    //###> API ###

    protected function getNextUser(): User
    {
        return $this->getReference(UserFixtures::getUserNameForProduct());
    }

    //###< API ###
}
