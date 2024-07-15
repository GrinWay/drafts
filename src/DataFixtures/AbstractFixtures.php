<?php

namespace App\DataFixtures;

use Faker\Generator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

abstract class AbstractFixtures extends Fixture
{
    protected static $usersForProductCount = 0;

    public function __construct(
        protected readonly Generator $faker,
    ) {
        //parent::__construct();
    }

    abstract public function load(
        ObjectManager $manager
    ): void;
}
