<?php

namespace App\Service\Router\Decorator;

use function Symfony\component\string\u;

use Symfony\Component\Routing\Attribute\Route as AttribureRoute;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Path;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class AttributeRouterReader extends \App\Service\Router\AttributeRouterReader
{
    public function __construct(
        string $kernelProjectDir,
        ?string $env = null,
        //
        protected $inner,
    ) {
        parent::__construct(
            kernelProjectDir: $kernelProjectDir,
            env: $env,
        );
    }

    public function load(mixed $resource, ?string $type = null): mixed
    {
        return $this->inner->load($resource, $type);
    }

    public function supports(mixed $resource, ?string $type = null): bool
    {
        return $this->inner->supports($resource, $type);
    }
}
