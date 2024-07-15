<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\UserPassport;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class UserPassportFixture extends AbstractFixtures
{
    public function __construct(
        $faker,
        #[Autowire(param: 'app.fixture.users')]
        private readonly int $count,
    ) {
        parent::__construct(
            faker: $faker,
        );
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < $this->count; ++$i) {
            $obj = new UserPassport(
                name: $this->faker->firstName,
                lastName: $this->faker->lastName,
                email: $this->faker->unique()->email,
            );

            $this->addReference(UserPassport::class . $i, $obj);

            $manager->persist($obj);
        }

        $manager->flush();
    }
}
