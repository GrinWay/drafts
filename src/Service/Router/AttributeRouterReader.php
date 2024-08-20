<?php

namespace App\Service\Router;

use function Symfony\component\string\u;

use Symfony\Component\Routing\Attribute\Route as AttribureRoute;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Path;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class AttributeRouterReader extends Loader
{
    private bool $isLoaded = false;

    public function __construct(
        private string $kernelProjectDir,
        protected ?string $env = null,
    ) {
        parent::__construct(env: $env);
    }

    /**
     * Loads a resource.
     *
     * @throws \Exception If something went wrong
     */
    public function load(mixed $resource, ?string $type = null): mixed
    {

        if (true === $this->isLoaded) {
            throw new \RuntimeException('Do not add the "extra" loader twice');
        }

        $routes = new RouteCollection();

        $pathToConfig = (string) u(Path::makeAbsolute(
            'config/',
            $this->kernelProjectDir
        ))->ensureEnd('/');
        $pathFromServicsToRouterAttributesDir = $resource['path'];
        $pathToRouterAttributes = (string) u(Path::canonicalize(
            $pathToConfig . $pathFromServicsToRouterAttributesDir,
        ))->ensureEnd('/');

        $finder = new Finder();
        // find all files in the current directory
        $finder
            ->files()
            ->in($pathToRouterAttributes)
        ;

        $fqcn = [];
        foreach ($finder as $absPath => $fileInfo) {
            //$tokens = \token_get_all(\file_get_contents($absPath, length: 512));
            $tokens = \token_get_all(\file_get_contents($absPath));

            for ($i = 0; $i < count($tokens); ++$i) {
                if ($tokens[$i][0] === \T_CLASS) {
                    $_fqcn = $resource['namespace'] . '\\' . $tokens[$i + 2][1];
                    ;
                    if (\class_exists($_fqcn)) {
                        $fqcn [] = $_fqcn;
                        break;
                    }
                }
            }
        }

        foreach ($fqcn as $class) {
            $refl = new \ReflectionClass($class);
            $methods = $refl->getMethods();
            foreach ($methods as $method) {
                $methodName = $method->getName();

                foreach ($method->getAttributes() as $methodAttribute) {
                    if ($methodAttribute->getName() == AttribureRoute::class) {
                        $routeArgs = $methodAttribute->getArguments();

                        $defaultRouteName = \strtolower(\preg_replace('~\\\~', '_', $class)) . '_' . $methodName;

                        $path = $routeArgs['path'] ?? $routeArgs[0] ?? null;
                        if ($path === null) {
                            throw new \Exception('"path" was not passed');
                        }

                        $defaults = [];
                        if (isset($routeArgs['defaults']) && !empty($routeArgs['defaults'])) {
                            $defaults = \array_merge($defaults, $routeArgs['defaults']);
                        }
                        if (isset($routeArgs[1]) && !empty($routeArgs[1])) {
                            $defaults = \array_merge($defaults, $routeArgs[1]);
                        }
                        $defaults['_controller'] = $class . '::' . $methodName;

                        $requirements = $routeArgs['requirements'] ?? $routeArgs[2] ?? [];
                        $routeName = $routeArgs['name'] ?? $defaultRouteName;

                        $route = new Route($path, $defaults, $requirements);
                        $routes->add($routeName, $route);
                    }
                }
            }
        }

        $this->isLoaded = true;

        return $routes;
    }

    /**
     * Returns whether this class supports the given resource.
     *
     * @param mixed $resource A resource
     */
    public function supports(mixed $resource, ?string $type = null): bool
    {
        return $type == 'attribute_route_reader';
    }
}
