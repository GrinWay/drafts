<?php

namespace App\Extension\Twig\Runtime;

use  function Symfony\Component\String\u;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Twig\Extension\RuntimeExtensionInterface;

class TwigFunctionExtension implements RuntimeExtensionInterface
{
    public function __construct()
    {
    }

    public static function getShortClass($objOrClass)
    {
        $refl = new \ReflectionClass($objOrClass);
        return $refl->getShortName();
    }
}
