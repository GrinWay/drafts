<?php

namespace App\Tests\Application\Controller;

use App\Tests\Application\AbstractApplicationCase;
use PHPUnit\Framework\Attributes as PHPUnitAttr;
use App\Test\DataProvider\LocaleProvider;

#[PHPUnitAttr\Large]
#[PHPUnitAttr\CoversNothing]
class ControllerTest extends AbstractApplicationCase {
	
	private $client;
	
	#[PHPUnitAttr\Before]
	public function before(): void {
		$this->client = static::createClient();
		$this->client->followRedirects(false);
	}
	
	#[PHPUnitAttr\After]
	public function after(): void {
		$this->client = null;
	}
	
	public function testHttpToHttpsForceRedirectingIsAwailable() {
		$this->client->request('GET', '/');
		$this->assertMatchesRegularExpression('~^3[0-9]{2}$~', $this->client->getResponse()->getStatusCode());
		
		$this->client->followRedirect();
		$this->assertResponseIsSuccessful();
	}

	#[PHPUnitAttr\DataProviderExternal(LocaleProvider::class, 'locales')]
	public function testAwailableLocales(string $_locale) {
		$this->client->request('GET', '/', server: [
			'HTTP_LOCALE' => $_locale,
		]);
		
		$this->assertSame($_locale, $this->client->getRequest()->getLocale());	
	}
}