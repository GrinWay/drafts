<?php

namespace App\Tests\E2E\ImmediateLoading;

use App\Tests\E2E\AbstractE2ECase;
use PHPUnit\Framework\Attributes as PHPUnitAttr;

#[PHPUnitAttr\RunTestsInSeparateProcesses]
#[PHPUnitAttr\CoversClass(HomeController::class)]
class HomeControllerTest extends AbstractE2ECase
{
	public function testImmediateLoading() {
		$client = static::createPantherClient();
		
		$crawler = $client->request('GET', '/product/types');
		
		$linkText = 'GET HOME INDEX INFO ABOUT THIS APPLICATION';
		$client->clickLink($linkText);
		
		$textThatWasLoaded = <<<'_TEXT_'
This application uses UX, Stimulus, Turbo and that's rather possible that it'll be using corona virus
_TEXT_;
		$this->assertSelectorWillContain(
			'body',
			$textThatWasLoaded,
		);
	}
}