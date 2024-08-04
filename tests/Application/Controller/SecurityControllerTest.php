<?php

namespace App\Tests\Application\Controller;

use App\Service;
use App\Repository;
use App\Tests\Application\AbstractApplicationCase;

class SecurityControllerTest extends AbstractApplicationCase
{
    public function testRegisterSubmission(): void
    {
		$client = static::createClient(
			server: [
				'HTTPS' => true,
			],
		);
		$client->followRedirects(true);
		$crawler = $client->request('GET', '/register');
		$form = $crawler->selectButton('Register')->form(method: 'POST');
		
		$this->assertFormValue('form', $form->getName().'[passport][name]', '');
		
		$this->assertInputValueSame($form->getName().'[passport][name]', '');
		$this->assertInputValueSame($form->getName().'[passport][lastName]', '');
		$this->assertInputValueSame($form->getName().'[email]', '');
		$this->assertInputValueSame($form->getName().'[roles]', '');
		$this->assertInputValueSame($form->getName().'[password]', '');
		// but it's only value, that's not about it's checked or not
		$this->assertInputValueSame($form->getName().'[agreeTerms]', '1');
		$this->assertCheckboxNotChecked($form->getName().'[agreeTerms]');
		
		$form[$form->getName().'[passport][name]'] = 'rudalv';
		$form[$form->getName().'[passport][lastName]'] = 'empty';
		$form[$form->getName().'[email]'] = 'vlad@dalv.rudalv';
		$form[$form->getName().'[roles]'] = 'ROLE_USER';
		$form[$form->getName().'[password]'] = '123123';
		$form[$form->getName().'[agreeTerms]']->tick();
		
		$crawler = $client->submit(
			$form,
			serverParameters: [
				'HTTP_ACCEPT_LANGUAGE' => 'ru',
			]
		);
		/*
		$crawler = $client->submit($form, [
		]);
		$client->submitForm('Register', [
			$form->getName().'[passport][name]' => 'rudalv',
			$form->getName().'[passport][lastName]'=> 'empty',
			$form->getName().'[email]' => 'vlad@dalv.rudalv',
			$form->getName().'[roles]' => 'ROLE_USER',
			$form->getName().'[password]' => '123123',
			$form->getName().'[agreeTerms]' => true,
		], 'POST');
		*/
		//$this->assertResponseIsSuccessful();
		$this->assertSelectorTextContains('title', '2FA');
	}
 
    public function test2FAAuth(): void
    {
		$client = static::createClient(
			server: [
				'HTTPS' => true,
			],
		);
		$client->followRedirects(true);
		
		$client->request('GET', '/login');
		$crawler = $client->submitForm('Login', [
			'_username' => 'test@owner.test',
			'_password' => '123123',
		]);
		$this->assertResponseIsSuccessful();
		$this->assertSelectorTextContains('title', '2FA');
    }
	
    public function testClickLink(): void
    {
		$client = static::createClient(
			server: [
				'HTTPS' => true,
			],
		);
		
		$crawler = $client->request('GET', '/');
		$crawlerHome = $client->clickLink('Home');
		$link = $crawler->selectLink('Home')->link();
		
		$uri = $link->getUri();
		
		$this->assertEquals('https://localhost/', $uri);
    }
	
    public function testCountOfQueries(): void
    {
		$client = static::createClient(
			server: [
				'HTTPS' => true,
			],
		);
		
		$client->enableProfiler();
		$client->request('GET', '/');
		$profiler = $client->getProfile();
		
		$queriesCount = $profiler->getCollector('db')->getQueryCount();
		$this->assertLessThan(2, $queriesCount);
    }
	
    public function testRequest(): void
    {
		$client = static::createClient(
			server: [
				'HTTPS' => true,
			],
		);
		$container = static::getContainer();
		$pa = $container->get('property_accessor');
		
		$request = $client->request('GET', '/');
		$request = $client->getRequest();
		$internalRequest = $client->getInternalRequest();
		$emailParam = $pa->getValue($request->attributes->all(), '[_route_params][email?]');
		
		$this->assertNotEquals(null, $emailParam);
		$this->assertEquals('https://localhost/', $internalRequest->getUri());
    }
	
    public function testCookieJar(): void
    {
		$client = static::createClient();
		
		$cookie = $client->getCookieJar();
		$this->assertCount(0, $cookie->all());
    }
    
	public function testClientHistory(): void
    {
		$client = static::createClient();
		
		$history = $client->getHistory();
		$this->assertEquals(true, $history->isEmpty());
    }
	
    public function testXmlHttpRequest(): void
    {
		$client = static::createClient(
			server: [
				'HTTPS' => true,
			],
		);
		//$client->catchExceptions(false);
		$container = static::getContainer();
		$client->followRedirects(true);
		
		$userRepo = $container->get(Repository\UserRepository::class);
		$testUser = $userRepo->findOneByEmail('test@owner.test');
		$client->loginUser($testUser);
		
		$client->xmlHttpRequest('POST', '/admin');
		$this->assertSelectorTextContains('title', 'ADMINS');
    }
	
    public function testAdminHomeWithAuthorizedOwner(): void
    {
		$client = static::createClient();
		$container = static::getContainer();
		$client->followRedirects(true);
		
		$userRepo = $container->get(Repository\UserRepository::class);
		$testUser = $userRepo->findOneByEmail('test@owner.test');
		$client->loginUser($testUser);
		
		$client->request('GET', '/admin');
		$this->assertSelectorTextContains('title', 'ADMINS');
    }
	
    public function testAdminHomeWithAuthorizedAdmin(): void
    {
		$client = static::createClient();
		$container = static::getContainer();
		$client->followRedirects(true);
		
		$userRepo = $container->get(Repository\UserRepository::class);
		$testUser = $userRepo->findOneByEmail('test@admin.test');
		$client->loginUser($testUser);
		
		$client->request('GET', '/admin');
		$this->assertSelectorTextContains('title', 'Home');
    }
	
    public function testAdminHomeWithAuthorizedUser(): void
    {
		$client = static::createClient();
		$container = static::getContainer();
		$client->followRedirects(true);
		
		$userRepo = $container->get(Repository\UserRepository::class);
		$testUser = $userRepo->findOneByEmail('test@user.test');
		$client->loginUser($testUser);
		
		$client->request('GET', '/admin');
		$this->assertSelectorTextContains('title', 'Home');
    }
	
    public function testAdminHomeWithNotAuthorized(): void
    {
		$client = static::createClient();
		$container = static::getContainer();
		$client->followRedirects(false);
		$client->enableProfiler();
		$client->request('GET', '/admin');
		$this->assertHttpClientRequestCount(0);
    }
}
