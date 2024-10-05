<?php

namespace App\Extension\Twig;

use  function Symfony\Component\String\u;

use Twig\Extension\AbstractExtension;
use App\Extension\Twig\Runtime\TwigFilterExtension;
use App\Extension\Twig\Runtime\TwigFunctionExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class TwigExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('for_user', [TwigFilterExtension::class, 'forUser']),
            new TwigFilter('string_attribute_as_array', [TwigFilterExtension::class, 'stringAttributeAsArray']),
        ];
    }

    public function getTokenParsers(): array
    {
        return [];
    }

    public function getNodeVisitors(): array
    {
        return [];
    }

    public function getTests(): array
    {
        return [];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_debug_type', \get_debug_type(...)),
            new TwigFunction('get_short_class', [TwigFunctionExtension::class, 'getShortClass']),
            new TwigFunction('random', [TwigFunctionExtension::class, 'random']),
        ];
    }

    public function getOperators(): array
    {
        return [];
    }
}
