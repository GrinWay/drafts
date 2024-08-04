<?php

namespace App\Tests\Unit\BuiltIn;

use App\Tests\Unit\AbstractUnitCase;

class BooleanTest extends AbstractUnitCase {
	public function test(): void {
		$this->assertTrue(true);
		$this->assertNotTrue(false);
		$this->assertFalse(false);
		$this->assertNotFalse(true);
	}
}