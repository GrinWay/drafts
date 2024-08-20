<?php

namespace App\Tests\Application;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Tests\InitTrait;
use Symfony\Component\HttpKernel\KernelInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\BrowserKit\AbstractBrowser;

abstract class AbstractApplicationCase extends WebTestCase {
	use InitTrait;
	
	protected function followRedirectsAfterRequest(object $client, bool $profile = true): void {
		if (!$client instanceof AbstractBrowser) {
			return;
		}
		while (\preg_match('~^3\d{2}$~', $client->getResponse()->getStatusCode())) {
			if (true === $profile) {
				$client->enableProfiler();
			}
			$client->followRedirect();			
		}
	}
}