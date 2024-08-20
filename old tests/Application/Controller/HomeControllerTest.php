<?php

namespace App\Tests\Application\Controller;

use PHPUnit\Framework\Attributes\DataProviderExternal;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use App\Entity\User;
use App\Service;
use App\Tests\Application\AbstractApplicationCase;
use App\Test\DataProvider\LocaleProvider;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\Attributes\Depends;

class HomeControllerTest extends AbstractApplicationCase {
	
	public function testLoggedInUserHasAtLeastUserRole() {
		$client = static::createClient();
		
		$user = new User();
		$client->loginUser($user);
		$token = self::getContainer()->get('security.token_storage')->getToken();
		
		$this->assertGreaterThanOrEqual(1, $token->getRoleNames());
		$this->assertContains('ROLE_USER', $token->getRoleNames());
	}
	
	/*
	public static function localesProvider(): array {
		return [
			['ru'],
			['en'],
		];
	}
	*/
	
	//#[DataProvider('localesProvider')]
	//#[TestDox('$_dataName $_locale')]
	#[DataProviderExternal(LocaleProvider::class, 'locales')]
	public function testLocales(string $_locale) {
		$client = static::createClient();
		$client->followRedirects(true);
		
		$client->request('GET', '/', server: [
			'HTTP_LOCALE' => $_locale,
		]);
	
		$this->assertResponseIsSuccessful();
		$this->assertSame($_locale, $client->getRequest()->getLocale());
	}
	
	public function testHomeHttpToHttps() {
		$client = static::createClient();
		$client->followRedirects(true);

		$crawler = $client->request('GET', '/');
		$request = $client->getRequest();
	//	\dd($request->attributes->all());
		//$this->assertRequestAttributeValueSame();
		$this->assertRouteSame('app_home_home');
		//$this->assertResponseHasCookie('PHPSESSID');
		
		$this->assertResponseNotHasHeader('X-Cache-Control');
		$this->assertResponseHasHeader('Cache-Control');
		//$this->assertResponseNotHasCookie('theme');
		//$this->assertResponseHeaderSame('Cache-Control', 'max-age=0, must-revalidate, private');
		//$this->assertResponseRedirects('https://127.0.0.1/');
		
		$this->assertResponseFormatSame('html');
		$this->assertResponseIsSuccessful();
		$this->assertResponseStatusCodeSame(200);
		$this->assertSelectorExists('title');
		$this->assertSelectorNotExists('titles');
		$this->assertSelectorTextContains('title', 'Home');
		//$this->assertAnySelectorTextContains('div', 'LOGIN LINK');
		//$this->assertAnySelectorTextSame('div', 'LOGIN LINK');
		$this->assertPageTitleSame('Welcome! Home Index');
	}
}