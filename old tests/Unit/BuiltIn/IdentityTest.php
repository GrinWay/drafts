<?php

namespace App\Tests\Unit\BuiltIn;

use App\Tests\Unit\AbstractUnitCase;
use PHPUnit\Framework\TestCase;

class IdentityTest extends TestCase //AbstractUnitCase
{
	public function test(): void {
		$this->assertSame(1E0, 1.);
		$this->assertNotSame(true, 2);
	}
}