<?php

namespace App\Tests\Application\Controller;

use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use App\Tests\Application\AbstractApplicationCase;
use PHPUnit\Framework\Attributes as PHPUnitAttr;
use App\Test\DataProvider\LocaleProvider;
use App\Controller;
use Symfony\Component\Notifier\Event\MessageEvent;
use Symfony\Component\Notifier\Message\MessageInterface;
use Symfony\Component\Notifier\Notification\Notification;

#[PHPUnitAttr\Large]
#[PHPUnitAttr\CoversClass(Controller\HomeController::class)]
class HomeControllerTest extends AbstractApplicationCase {
	
	public function testNotifierMessengesSent() {
		$client = static::createClient();
		$client->followRedirects(true);
		
		$crawler = $client->request('GET', '/');
		
		$event = $this->getNotifierEvent();
		$notification = $this->getNotifierMessage();
		
		//###> ASSERT ###
		//$this->assertNotificationCount(1, 'telegram');
		$this->assertQueuedNotificationCount(1, 'telegram');
		
		/*
		if ($event instanceof MessageEvent) {
			$this->assertNotificationIsQueued($event);			
		}
		
		if ($notification instanceof MessageInterface) {
			$this->assertNotificationSubjectContains($notification, 'Hello');
			$this->assertNotificationTransportIsEqual($notification, 'telegram');
		}
		*/
		//###< ASSERT ###
	}
}