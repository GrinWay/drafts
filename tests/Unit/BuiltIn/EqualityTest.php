<?php

namespace App\Tests\Unit\BuiltIn;

use function Symfony\Component\String\u;

use App\Tests\Unit\AbstractUnitCase;

// всё что иммет в названии Equals сравнивается как ==
class EqualityTest extends AbstractUnitCase {
	public function test(): void {
		$this->assertEquals(true, 1);
		
		$expected = new \DateTimeImmutable('2023-02-23 01:23:45');
        $actual   = new \DateTimeImmutable('2023-02-23 01:23:45');
		$this->assertEquals($expected, $actual);
		
		$this->assertEqualsCanonicalizing(
			[
				'a',
				'b',
			],
			[
				'b',
				'a',
			],
		);
		
		$this->assertEqualsIgnoringCase(['A'], ['a']);
		
		$o1 = new ClassWithEqualsMethod('Alice');
		$o2 = new ClassWithEqualsMethod('alice');
		$this->assertObjectEquals($o1, $o2);
		
		$file1 = __DIR__.'/../../../.env';
		$file2 = __DIR__.'/../../../.env';
		$this->assertFileEquals($file1, $file2);
		
	}
}

/**
* @internal
*/
class ClassWithEqualsMethod {
	public function __construct(
		public readonly string $name,
	) {}
	
	public function equals(ClassWithEqualsMethod $obj): bool {
		return u($this->name)->ignoreCase()->equalsTo($obj->name);
	}
}