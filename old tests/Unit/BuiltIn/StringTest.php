<?php

namespace App\Tests\Unit\BuiltIn;

use App\Tests\Unit\AbstractUnitCase;

class StringTest extends AbstractUnitCase {
	public function test(): void {
		$this->assertStringStartsWith('start', 'start with this');
		
		$this->assertStringEndsWith(' ', 'start with this ');
		
		$this->assertStringContainsString('st', 'start with this');
		
		$this->assertStringContainsStringIgnoringCase('ST', 'start with this');
		
		$this->assertMatchesRegularExpression('~^\w{5}\s{1}~', 'start with this');
		
		$this->assertStringMatchesFormat('%s', 'start with this');
		
		/*
		$this->assertStringEqualsFile(
			__DIR__.'/../../../init.sh',
			<<<'FILE_CONTENT'
			bash "./public/deploy/install-grinway-symfony-bundles.sh"

			php bin/console make:migration
			php bin/console doctrine:schema:drop -f --full-database
			php bin/console doctrine:migrations:migrate -q
			php bin/console doctrine:fixture:load -q

			yarn install
			yarn run dev
			FILE_CONTENT,
		);
		*/
		
		
	}
}