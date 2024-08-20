<?php

namespace App\Tests\Unit\DomCrawler;

use App\Tests\Unit\AbstractUnitCase;
use Symfony\Component\DomCrawler\UriResolver;
use PHPUnit\Framework\Attributes as PHPUnitAttr;

#[PHPUnitAttr\CoversNothing]
class UriResolverTest extends AbstractUnitCase
{
	#[PHPUnitAttr\TestWith(['/abc/efg'])]
	#[PHPUnitAttr\TestWith(['/abc/efg/'])]
	#[PHPUnitAttr\TestWith(['abc/efg'])]
	#[PHPUnitAttr\TestWith(['abc/efg/'])]
	#[PHPUnitAttr\TestDox('Valid wnen first relative $relPath and second absolute')]
	public function testValidWnenFirstRelativeAndSecondAbsolute($relPath) {
		$uri = UriResolver::resolve($relPath, 'https://127.0.0.1:8000');
		
		$this->assertSame('https://127.0.0.1:8000/'.\ltrim($relPath, '/'), $uri);
	}
}