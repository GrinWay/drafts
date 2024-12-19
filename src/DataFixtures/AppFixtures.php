<?php

namespace App\DataFixtures;

use App\Factory\TodoFactory;
use App\Factory\UserFactory;
use App\Story\FoundryStory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use PHPUnit\Framework\Constraint\ExceptionMessageIsOrContains;
use Symfony\Cmf\Bundle\RoutingBundle\Doctrine\Orm\Route;
use Symfony\Cmf\Component\Routing\ContentRepositoryInterface;
use Symfony\Cmf\Component\Routing\RouteObjectInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class AppFixtures extends Fixture
{
    private $manager;

    public function __construct(#[Autowire('@cmf_routing.content_repository')] private readonly ContentRepositoryInterface $contentRepository, private readonly EntityManagerInterface $em)
    {

    }

    public function load(ObjectManager $manager): void
    {
        $this->loadDynamicRoutes();

        FoundryStory::load();

        TodoFactory::createMany(10);

        UserFactory::createMany(3);
    }

    private function loadDynamicRoutes()
    {
        $route = new Route();
        $route->setName('HARDCODED_ROUTE_KEY');
        $route->setStaticPrefix('/dynamic');
        $route->setDefault(RouteObjectInterface::CONTENT_ID, $this->contentRepository->getContentId(null));
        $route->setContent(new \StdClass());

        $this->em->persist($route);
        $this->em->flush();
    }
}
