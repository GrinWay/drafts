<?php

namespace App\DataFixtures;

use App\Type\Product\FurnitureProductColorType;
use Carbon\CarbonImmutable;
use App\Entity\Product\FurnitureProduct;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use App\Entity\TaskTopic\TaskFoodTopic;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Service\Doctrine\TaskEntityUtils;

class TaskFoodTopicFixtures extends AbstractProductFixtures implements FixtureGroupInterface//, DependentFixtureInterface
{
    public function __construct(
        $faker,
        #[Autowire('%app.fixture.product.task_food_topic%')]
        private readonly int $count,
        private $enUtcCarbon,
        private SluggerInterface $slugger,
    ) {
        parent::__construct(
            faker: $faker,
        );
    }

    public function load(
        ObjectManager $manager
    ): void {
        for ($i = 0; $i < $this->count; ++$i) {
            $name = \strtoupper($this->faker->word());

            $obj = new TaskFoodTopic(
                name: $name,
            );

			$this->addReference(self::class.$i, $obj);

            $manager->persist($obj);
        }

        $manager->flush();
    }

    /* DependentFixtureInterface */
    public function getDependencies()
    {
        return [
        ];
    }

    /* FixtureGroupInterface */
    public static function getGroups(): array
    {
        return [
        ];
    }

    //###> HELPER ###
}
