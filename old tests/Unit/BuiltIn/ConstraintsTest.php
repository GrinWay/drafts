<?php

namespace App\Tests\Unit\BuiltIn;

use App\Tests\Unit\AbstractUnitCase;

class ConstraintsTest extends AbstractUnitCase {
	public function test(): void {
		$this->assertThat(
			new \StdClass(),
			$this->isInstanceOf(\StdClass::class),
		);
	}
}