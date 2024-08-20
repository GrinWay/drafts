<?php

namespace App\Tests\Unit\BuiltIn;

use App\Tests\Unit\AbstractUnitCase;

class ObjectTest extends AbstractUnitCase {
	public function test(): void {
		$o = new \StdClass();
		$o->name = 'stdName';
		$this->assertObjectHasProperty('name', $o);
	}
}