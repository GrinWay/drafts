<?php

namespace App\Tests\Unit\BuiltIn;

use App\Tests\Unit\AbstractUnitCase;

class JSONTest extends AbstractUnitCase {
	public function test(): void {
		$this->assertJson('{"name": "Alex"}');
		
		$this->assertJsonStringEqualsJsonString('{"name": "Alex"}', '{"name": "Alex"}');
	}
}