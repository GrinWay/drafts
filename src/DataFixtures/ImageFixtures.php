<?php

namespace App\DataFixtures;

use App\Type\Product\FurnitureProductColorType;
use Carbon\CarbonImmutable;
use App\Entity\Product\FurnitureProduct;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use App\Entity\Media\Image;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Service\Doctrine\TaskEntityUtils;
use App\Service\StringService;
use Vich\UploaderBundle\Entity\File as VichFile;

class ImageFixtures extends AbstractProductFixtures implements FixtureGroupInterface//, DependentFixtureInterface
{
    public function __construct(
        $faker,
        #[Autowire('%app.fixture.product.image%')]
        private readonly int $count,
        private StringService $stringService,
    ) {
        parent::__construct(
            faker: $faker,
        );
    }

    public function load(
        ObjectManager $manager
    ): void {
        for ($i = 0; $i < $this->count; ++$i) {
            $filepath = $this->stringService->getPath(
				$this->faker->unique()->numberBetween(1, 5).'.jpg',
			);
			$vichFile = new VichFile();
			$vichFile->setName($filepath);
			
            $obj = new Image(
				filepath: $filepath,
				fileOriginalName: $filepath,
				//vichFile: $vichFile,
            );

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
