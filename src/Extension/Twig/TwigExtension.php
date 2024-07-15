<?php

namespace App\Extension\Twig;

use  function Symfony\Component\String\u;

use Twig\Extension\AbstractExtension;
use App\Extension\Twig\Runtime\TwigFilterExtension;
use Twig\TwigFilter;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class TwigExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('for_user', [TwigFilterExtension::class, 'forUser']),
        ];
    }

    public function getTokenParsers()
    {
        return [];
    }

    public function getNodeVisitors()
    {
        return [];
    }

    public function getTests()
    {
        return [];
    }

    public function getFunctions()
    {
        return [];
    }

    public function getOperators()
    {
        return [];
    }
}
