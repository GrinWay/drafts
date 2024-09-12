<?php

namespace App\DataFixtures;

use App\Type\Product\FurnitureProductColorType;
use Carbon\CarbonImmutable;
use App\Entity\Product\FurnitureProduct;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use App\Entity\Task;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Service\Doctrine\TaskEntityUtils;

class TaskFixtures extends AbstractProductFixtures implements FixtureGroupInterface, DependentFixtureInterface
{
    public function __construct(
        $faker,
        #[Autowire('%app.fixture.product.task%')]
        private readonly int $count,
        #[Autowire('%app.fixture.product.task_food_topic%')]
        private readonly int $taskFoodTopicCount,
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
            $name = $this->faker->name();
            if ($this->faker->numberBetween(0, 1)) {
                $deadLine = $this->enUtcCarbon->now()->sub($this->faker->numberBetween(0, 1000), 'days');
            } else {
                $deadLine = $this->enUtcCarbon->now()->add($this->faker->numberBetween(0, 1000), 'days');
            }
            $topic = $this->getReference(TaskFoodTopicFixtures::class . $this->faker->numberBetween(0, $this->taskFoodTopicCount - 1));

            $obj = new Task(
                name: $name,
                deadLine: $deadLine,
                topic: $topic,
            );

            $manager->persist($obj);
        }

        $manager->flush();
    }

    /* DependentFixtureInterface */
    public function getDependencies()
    {
        return [
            TaskFoodTopicFixtures::class,
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
