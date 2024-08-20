<?php

namespace App\Tests\Application\Messenger;

use App\Tests\Application\AbstractApplicationCase;

class TransportsTest extends AbstractApplicationCase {
	public function testSyncTransport() {
		$client = static::createClient();
		$client->followRedirects(true);
		
		$client->request('GET', '/messenger');
		$syncTransport = self::getContainer()->get('messenger.transport.sync');
		$syncTransportSent = $syncTransport->getSent();
		
		$this->assertResponseIsSuccessful();
		$this->assertCount(1, $syncTransportSent);
	}
}