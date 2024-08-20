<?php

namespace App\Tests\Application\SwitchLocale;

use Symfony\Component\PropertyAccess\PropertyAccess;
use App\Tests\Application\AbstractApplicationCase;

class SwitchLocaleTest extends AbstractApplicationCase {
	public function testSwithcLocale(): void {
		$client = static::createClient();
		$client->request('GET', '/', server: [
			'HTTP_LOCALE' => $passedLocale = 'en_US.UTF-8',
		]);
		$gotLocale = $client->getRequest()->getLocale();
		
		$this->assertEquals($passedLocale, $gotLocale);
	}
}