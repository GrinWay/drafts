<?php

namespace App\Tests\E2E;

use Symfony\Component\Panther\PantherTestCase;
use App\Tests\InitTrait;
use Symfony\Component\Panther\Client;
use Symfony\Component\Filesystem\Path;

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
	
	protected function takeScreenshot(object $client, ?string $filename = null): void {
		if ($client instanceof Client) {
			$filename ??= \sprintf('img_%s.png', \time());
			$root = $this->screenshotsRoot ?? './var/cache/panther/screenshots';
			$to = $this->stringService->getPath($root, $filename);
			$client->takeScreenshot($to);
		}
	}
	
	//###< API ###
}
