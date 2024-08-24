<?php

namespace App\Service;

use Twig\Environment as Twig;

//TODO: TwigUtil
class TwigUtil
{
    public function __construct(
		private readonly Twig $twig,
	) {}
	
	//###> API ###
	/*
	 * Resolves namespaces, described by configuration: twig.paths: [real_path: namespace]
	 * Behaves like twig "source('@namespace/filename')" built-in function
	 * 
	 * @return string (Returns resolved resource path: @namespace/filename -> real_path/filename)
	 */
	public function getLocatedResource(string $resource): string {
		return $this->twig->getLoader()->getSourceContext($resource)->getPath();
	}
	//###< API ###
}
