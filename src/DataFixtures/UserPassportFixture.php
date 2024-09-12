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
            $sign = $this->faker->randomElement(['+', '-']);
            $h = $this->faker->numberBetween(0, 23);
            if (1 === \strlen($h)) {
                $h = '0' . $h;
            }
            $m = $this->faker->numberBetween(0, 59);
            if (1 === \strlen($m)) {
                $m = '0' . $m;
            }
            $timezone = \sprintf('%s%s:%s', $sign, $h, $m);
            $lang = $this->faker->languageCode;

            $obj = new UserPassport(
                name: $this->faker->firstName,
                lastName: $this->faker->lastName,
                timezone: $timezone,
                lang: $lang,
                //email: $this->faker->unique()->email,
            );

            $this->addReference(UserPassport::class . $i, $obj);

            $manager->persist($obj);
        }

        $manager->flush();
    }
}
