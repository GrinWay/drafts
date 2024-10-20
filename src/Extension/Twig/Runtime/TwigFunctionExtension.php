<?php

namespace App\Extension\Twig\Runtime;

use  function Symfony\Component\String\u;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use App\Service;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Twig\Extension\RuntimeExtensionInterface;

class TwigFunctionExtension implements RuntimeExtensionInterface
{
    public function __construct(
		private readonly Service\TwigUtil $twigUtil,
	) {
    }

    public static function getShortClass($objOrClass)
    {
        $refl = new \ReflectionClass($objOrClass);
        return $refl->getShortName();
	}
    
	public function random() {
		return \Carbon\Carbon::now('UTC')->second.\random_int(0, 10);
	}

	public function resolveTwigNamespace(string $resource): string {
		return $this->twigUtil->resolveTwigNamespace($resource);
	}
}
