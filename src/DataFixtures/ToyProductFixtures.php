<?php

namespace App\DataFixtures;

use Carbon\CarbonImmutable;
use App\Entity\Product\ToyProduct;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use App\Entity\ProductPassport;

class ToyProductFixtures extends AbstractProductFixtures implements FixtureGroupInterface, DependentFixtureInterface
{
    public function __construct(
        $faker,
        #[Autowire(param: 'app.fixture.product.toy')]
        private readonly int $count,
    ) {
        parent::__construct(
            faker: $faker,
        );
    }

    public function load(
        ObjectManager $manager
    ): void {
        for ($i = 0; $i < $this->count; ++$i) {
            $name = $this->faker->name();
            $price = $this->faker->numberBetween(59, 5904);
            $description = $this->faker->text();
            $isPublic = $this->faker->boolean;
            $forKidsMoreThan = $this->faker->randomElement([18, 20, 5, 10]);
            $pp = new ProductPassport(
                $this->faker->firstName,
                [
                    'type11',
                    'type22',
                    'type3',
                ]
            );
            $productPassport = $this->faker->numberBetween(0, 1) ? $pp : null;
            $user = $this->getNextUser();

            $product = new ToyProduct(
                name: $name,
                price: $price,
                description: $description,
                isPublic: $isPublic,
                forKidsMoreThan: $forKidsMoreThan,
                passport: $productPassport,
                user: $user,
            );

            $manager->persist($product);
        }

        $manager->flush();
    }

    /* DependentFixtureInterface */
    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }

    /* FixtureGroupInterface */
    public static function getGroups(): array
    {
        return [
            'product',
        ];
    }

    //###> HELPER ###

    //###< HELPER ###
}
