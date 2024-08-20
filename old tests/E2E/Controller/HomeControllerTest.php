<?php

namespace App\Tests\E2E\Controller;

use function Symfony\Component\String\u;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Panther\PantherTestCase;
use Symfony\Component\Panther\Client;
use App\Tests\E2E\AbstractE2ECase;
use App\Service\TestUtil;

class HomeControllerTest extends AbstractE2ECase
{
    public function testHome(): void
    {
		$container = self::bootKernel()->getContainer();
		// no
		//self::bootKernel();
		// no
        //$client = static::createClient();
		// no
		//$client = Client::createChromeClient();
		
		/**
		* default:
		* - port 443
		* - http (https PANTHER_CHROME_ARGUMENTS='--ignore-certificate-errors')
		*/
		$client = static::createPantherClient(
			managerOptions: [
				'chromedriver_arguments' => [
					'--log-path=myfile.log',
					'--log-level=DEBUG',
					// no
					//'--ignore-certificate-errors',
				],
				'capabilities' => [
					'goog:loggingPrefs' => [
						'browser' => 'ALL',
						'performance' => 'ALL',
					],
				],
			],
		);
		
		$client->followRedirects(true);
		
		$client->request('GET', '/');
		$this->ping($client);
		
		$crawler = $client->clickLink('Login');
        
		//$crawler = $client->waitFor('form');
		// Ensure that this element exists ->text()
		$crawler->filter('button[type="submit"]')->text();
		//$client->waitForDisabled('button[type="submit"]');
		$crawler = $client->waitForVisibility('form');
		
		//###> FETCH
		$formChildren = $crawler->filter('form input')->extract(['name']);
		
		/*
		$title = $crawler->filter('head title')->text();
		\dd($title);
		*/

		$formChildrenContainRegex = false;
		$regex = '~(?<t>pass)~i';
		foreach($formChildren as $nameAttr) {
			
			if (!isset(u($nameAttr)->match($regex)['t'])) {
				continue;
			}
			
			$formChildrenContainRegex = true;
		}
		
		//###> ASSERT
		if (false === $formChildrenContainRegex) {
			$this->assertSame(\sprintf('There is no any form field contains: "%s"', $regex), 'INDEED');
		}
		//$this->assertResponseIsSuccessful('Статус код не "2xx"');
		$this->assertPageTitleSame('Log in!');
		$this->assertGreaterThanOrEqual(2, \count($formChildren));
		$this->assertSelectorTextContains('body', 'Login');
		
		//###> PANTHER ###
		$this->assertSelectorIsEnabled('[name=_csrf_token]');
		$this->assertSelectorIsNotVisible('[name=_csrf_token]');
		$this->assertSelectorAttributeContains('[name=_csrf_token]', 'type', 'hidden');
		$this->assertSelectorWillBeEnabled('[name=_csrf_token]');
		//###< PANTHER ###
		
		$consoleLogs = $client->getWebDriver()->manage()->getLog('performance');
		
		$this->takeScreenshot($client);
    }
}
