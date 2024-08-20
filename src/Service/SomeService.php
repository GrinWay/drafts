<?php

namespace App\Service;

use App\Contract\Some\SomeServiceInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;
use Symfony\Component\DependencyInjection\Attribute\Lazy;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\TaggedLocator;
use Symfony\Component\DependencyInjection\ServiceLocator;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Psr\Container\ContainerInterface;
use App\Attribute\Hash\HashGetter;

class SomeService extends AbstractStringService
{
    use \App\Trait\RequireEntityManager;

    private ?EntityManagerInterface $em = null;

    public function __construct(
        private $locator,
    ) {
    }

    function &getEntityManagerRef(): ?EntityManagerInterface
    {
        return $this->em;
    }

    // uncomment to deny #[Required]
    //public function requireEntityManager() {}

    public static function create(): static
    {
        \dump(__METHOD__);
        return new static();
    }

    public function publicMethod(): void
    {
        \dump(__METHOD__);
    }

    public function __invoke()
    {
        return md5(rand(0, 100));
    }

    public function getGenerator(...$args): \Generator
    {
        yield ['data' => $args];
    }
}
