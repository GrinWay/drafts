<?php

namespace App\Tests\Unit\BuiltIn;

use App\Tests\Unit\AbstractUnitCase;

class TypesTest extends AbstractUnitCase {
	public function test(): void {
		$this->assertInstanceOf(\StdClass::class, new \StdClass());
		
		$this->assertIsArray([]);
		
		$this->assertIsBool(!!1);
		
		$this->assertIsCallable(static fn() => true);
		
		$this->assertIsFloat(1E1);
		
		$this->assertIsInt(1);
		
		$this->assertIsIterable((static fn() => yield 1)());
		
		$this->assertIsNumeric('1.');
		
		$this->assertIsObject($o = new \StdClass());
		
		//$this->assertIsResource(null);
		
		$this->assertIsScalar('sd');
		
		$this->assertIsString($o::class);
		
		$this->assertNull(null);
	}
}