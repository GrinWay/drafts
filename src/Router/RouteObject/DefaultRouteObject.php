<?php

namespace App\Router\RouteObject;

use Symfony\Cmf\Component\Routing\RouteObjectInterface;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;

//#[AsAlias('app.route_object.default')]
class DefaultRouteObject implements RouteObjectInterface
{
    public function getContent(): ?object
    {
        $stdObject = new \StdClass();
        $stdObject->content = 'HARDCODED DYNAMIC CONTENT';
        return $stdObject;
    }

    public function getRouteKey(): ?string
    {
        return 'HARDCODED_ROUTE_KEY';
    }
}
