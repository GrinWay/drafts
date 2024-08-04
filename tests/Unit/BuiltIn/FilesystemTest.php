<?php

namespace App\Tests\Unit\BuiltIn;

use App\Tests\Unit\AbstractUnitCase;

class FilesystemTest extends AbstractUnitCase {
	public function test(): void {
		$this->assertIsReadable(__FILE__);
		
		$this->assertIsWritable(__DIR__);
		
		
		$this->assertDirectoryExists(__DIR__);
		
		$this->assertDirectoryIsReadable(__DIR__);
		
		$this->assertDirectoryIsWritable(__DIR__);
		
		
		$this->assertFileExists(__FILE__);
		
		$this->assertFileIsReadable(__FILE__);
		
		$this->assertFileIsWritable(__FILE__);
	}
}