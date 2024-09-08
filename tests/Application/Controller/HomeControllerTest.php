<?php

namespace App\Tests\Application\Controller;

use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use App\Tests\Application\AbstractApplicationCase;
use PHPUnit\Framework\Attributes as PHPUnitAttr;
use App\Test\DataProvider\LocaleProvider;
use App\Controller;

#[PHPUnitAttr\Large]
#[PHPUnitAttr\CoversClass(Controller\HomeController::class)]
class HomeControllerTest extends AbstractApplicationCase {
	
	#[PHPUnitAttr\Before]
	public function before(): void {
		$this->client = static::createClient();
		$this->client->followRedirects(true);
	}
	
	#[PHPUnitAttr\After]
	public function after(): void {
		$this->client = null;
	}
	
	public function testNotifierMessengesSent() {
		$client = static::createClient();
		$client->followRedirects(true);
		
		$crawler = $client->request('GET', '/');
		
		//\dump($crawler->filter('html')->text());
		
		//TODO: current
		$this->assertNotificationCount(1);
	}
}