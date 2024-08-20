<?php

namespace App\Tests\E2E\Controller;

use App\Tests\E2E\AbstractE2ECase;
use PHPUnit\Framework\Attributes as PHPUnitAttr;

#[PHPUnitAttr\RunTestsInSeparateProcesses]
#[PHPUnitAttr\CoversClass(HomeController::class)]
class HomeControllerTest extends AbstractE2ECase
{
	public function testValidGetOnHomePage() {
		$client = static::createPantherClient();
		
		$crawler = $client->request('GET', '/');
		
		$this->assertSame(200, $client->getInternalResponse()->getStatusCode(),
			message: 'The response is not successfull.',
		);
		
		$this->takeScreenshot($client, __FUNCTION__);
	}
	
	public function testInvalidPostOnHomePage() {
		$client = static::createClient();
		$client->followRedirects(true);
		
		$client->request('POST', '/');
		
		$this->assertMatchesRegularExpression('~^4[0-9]{2}$~', $client->getResponse()->getStatusCode(),
			message: 'Method post is awailable on the "/".',
		);
	}
}