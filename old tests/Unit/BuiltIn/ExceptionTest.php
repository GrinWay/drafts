<?php

namespace App\Tests\Unit\BuiltIn;

use App\Tests\Unit\AbstractUnitCase;

class ExceptionTest extends AbstractUnitCase {
	public function testException(): void {
		$this->expectException(\Exception::class);
		
		throw new \Exception();
	}
}