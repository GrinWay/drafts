<?php

namespace App\Tests\Unit\BuiltIn;

use App\Tests\Unit\AbstractUnitCase;

class CardinalityTest extends AbstractUnitCase {
	public function test(): void {
		$this->assertCount(4, [1, 3, 4, 4]);
		
		$this->assertSameSize([1, 2], [11, 22]);
		
		$this->assertEmpty(0x0);
		
		$this->assertGreaterThan(5, 6);
		
		$this->assertGreaterThanOrEqual(5, 5);
		
		$this->assertLessThan(1, 0);
		
		$this->assertLessThanOrEqual(1, 1);
	}
}