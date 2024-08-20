<?php

namespace App\Tests\Unit\BuiltIn;

use App\Tests\Unit\AbstractUnitCase;
use PHPUnit\Framework\Attributes\RequiresPhp;

#[RequiresPhp('>=7')]
class OutputTest extends AbstractUnitCase {
	public function testOutputString(): void {
		$this->expectOutputString('test');
		
		echo 'test';
	}
	
	public function testOutputRegexStartsWithTIgnoringCase(): void {
		$this->expectOutputRegex('~^t~i');
		
		echo 'TesT';
	}
}