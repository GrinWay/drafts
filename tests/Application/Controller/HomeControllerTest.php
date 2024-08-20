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
	
	public function testHomePageGetMethodIsSuccessful() {
		$this->client->request('GET', '/');
		
		$this->assertResponseIsSuccessful();
	}
	
	public function testHomePagePostMethodIsForbidden() {
		//$this->expectException(MethodNotAllowedHttpException::class);
		
		$this->client->request('POST', '/');
		
		$this->assertMatchesRegularExpression('~^4[0-9]{2}$~', $this->client->getResponse()->getStatusCode());
	}
}