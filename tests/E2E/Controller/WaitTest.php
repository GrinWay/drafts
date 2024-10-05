<?php

namespace App\Tests\E2E\Controller;

use App\Tests\E2E\AbstractE2ECase;
use PHPUnit\Framework\Attributes as PHPUnitAttr;

#[PHPUnitAttr\RunTestsInSeparateProcesses]
#[PHPUnitAttr\CoversClass(HomeController::class)]
class WaitTest extends AbstractE2ECase
{
	public function testWait() {
		$client = static::createPantherClient();
		
		$crawler = $client->request('GET', '/');

		$this->assertSelectorIsVisible('[data-controller="some-content"]');
		
		$client->waitFor($btnDefaultTargetCssSelector = "button[data-some-content-target='default']");
		
		$crawlerBtn = $crawler->filter($btnDefaultTargetCssSelector);
		
		$x = $crawlerBtn->getLocation()->getX();
		$y = $crawlerBtn->getLocation()->getY();
		$scrollToBtnJs = "window.scrollTo({$x}, {$y})";
		$client->executeScript($scrollToBtnJs);
		
		$clickBtnJs = "document.querySelector(\"{$btnDefaultTargetCssSelector}\").click()";
		$client->executeScript($clickBtnJs);
		
		$this->assertSelectorNotExists($btnDefaultTargetCssSelector, message: "{$btnDefaultTargetCssSelector} was REMOVED");
	}
}