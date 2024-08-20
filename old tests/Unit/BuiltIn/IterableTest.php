<?php

namespace App\Tests\Unit\BuiltIn;

use App\Tests\Unit\AbstractUnitCase;

class IterableTest extends AbstractUnitCase {
	public function test(): void {
		$this->assertArrayHasKey('processor', [1, 2, 3, 'processor' => 'nice-core-i10']);
		
		// ===
		$this->assertContains(4, [1, 2, 3, 4]);
		
		// ==
		$this->assertContainsEquals(4, ['4']);
		
		$this->assertContainsOnly(
			'integer',
			[
				1,
				2,
				3,
			],
		);
		
		$this->assertContainsOnlyInstancesOf(
			\StdClass::class,
			[
				new \StdClass(),
				new \StdClass(),
				new \StdClass(),
			],
		);
	}
}