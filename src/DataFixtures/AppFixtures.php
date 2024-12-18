<?php

namespace App\DataFixtures;

use App\Factory\UserFactory;
use App\Story\FoundryStory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        FoundryStory::load();

        UserFactory::createMany(3);
    }
}
