<?php

namespace App\Tests\Application\Controller;

use App\Tests\Application\AbstractApplicationCase;

class HomeControllerTest extends AbstractApplicationCase {
	public function testHomeHttpToHttps() {
		$client = static::createClient();
		$client->followRedirects(false);

		$crawler = $client->request('GET', 'http://127.0.0.1:8000');
		$request = $client->getRequest();
	//	\dd($request->attributes->all());
		//$this->assertRequestAttributeValueSame();
		$this->assertRouteSame('app_home_home');
		//$this->assertResponseHasCookie('PHPSESSID');
		
		$this->assertResponseNotHasHeader('X-Cache-Control');
		$this->assertResponseHasHeader('Cache-Control');
		$this->assertResponseNotHasCookie('theme');
		$this->assertResponseHeaderSame('Cache-Control', 'max-age=0, must-revalidate, private');
		$this->assertResponseRedirects('https://127.0.0.1/');
		
		$client->followRedirect();
		$this->assertResponseFormatSame('html');
		$this->assertResponseIsSuccessful();
		$this->assertResponseStatusCodeSame(200);
		$this->assertSelectorExists('title');
		$this->assertSelectorNotExists('titles');
		$this->assertSelectorTextContains('title', 'Home');
		$this->assertAnySelectorTextContains('div', 'LOGIN LINK');
		$this->assertAnySelectorTextSame('div', 'LOGIN LINK');
		$this->assertPageTitleSame('Welcome! Home Index');
	}
}