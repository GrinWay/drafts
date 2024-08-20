<?php

namespace App\Tests\E2E;

use function Symfony\Component\String\u;

use PHPUnit\Framework\Attributes as PHPUnitAttr;
use Symfony\Component\Panther\PantherTestCase;
use App\Service;
use App\Tests\InitTrait;
use Symfony\Component\Panther\Client;
use Symfony\Component\Filesystem\Path;
use App\Controller\HomeController;
use Symfony\Component\String\Slugger\SluggerInterface;

class AbstractE2ECase extends PantherTestCase
{
	use InitTrait;
	
	private string $screenshotsRoot;
	private Service\StringService $stringService;
	private SluggerInterface $slugger;
	
	#[PHPUnitAttr\Before]
	protected function before(): void {
		$container = self::getContainer();
		
		$this->screenshotsRoot = $container->getParameter('app.panther.screenshots');
		$this->stringService = $container->get(Service\StringService::class);
		$this->slugger = $container->get('slugger');
	}
	
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
			$filename ??= \sprintf('img_%s', \time());
			$filename = (string) u($this->slugger->slug($filename))->ensureEnd('.png');
			$root = $this->screenshotsRoot ?? './var/cache/panther/screenshots';
			$to = $this->stringService->getPath($root, $filename);
			$client->takeScreenshot($to);
		}
	}
	
	//###< API ###
}
