<?php

namespace App\Tests\E2E;

use Symfony\Component\Panther\PantherTestCase;
use App\Tests\InitTrait;
use Symfony\Component\Panther\Client;

class AbstractE2ECase extends PantherTestCase
{
	use InitTrait;
	
	
	//###> API ###
	
	protected static function ping(object $client, ?string $message = null): void {
		if ($client instanceof Client) {
			if ($client->ping()) {
				$message ??= 'WebDriver is still working...';
				\dump($message);
			}			
		}
	}
	
	//###< API ###
}
