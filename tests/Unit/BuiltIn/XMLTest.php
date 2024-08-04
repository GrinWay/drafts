<?php

namespace App\Tests\Unit\BuiltIn;

use App\Tests\Unit\AbstractUnitCase;

class XMLTest extends AbstractUnitCase {
	public function test (): void {
		$this->assertXmlStringEqualsXmlString(
            '<foo><bar/></foo>',
            '<foo><bar/></foo>',
        );
	}
}