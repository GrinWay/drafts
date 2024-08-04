<?php

namespace App\Tests\Unit\BuiltIn;

use App\Tests\Unit\AbstractUnitCase;

class MathTest extends AbstractUnitCase {
	public function test(): void {
		$this->assertInfinite(INF);
		
		$this->assertNan(NAN);
	}
}