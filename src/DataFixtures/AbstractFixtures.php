<?php

namespace App\DataFixtures;

use App\Trait\ServiceLocator\Aware\SecurityLoggerAware;
use App\Trait\ServiceLocator\Aware\UserPasswordHasherAware;
use Symfony\Contracts\Service\ServiceMethodsSubscriberTrait;
use Faker\Generator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Contracts\Service\ServiceSubscriberInterface;

abstract class AbstractFixtures extends Fixture implements ServiceSubscriberInterface
{
	use ServiceMethodsSubscriberTrait;
	use SecurityLoggerAware;
	use UserPasswordHasherAware;

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
