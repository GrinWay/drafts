<?php

namespace App\Tests\Application\Controller;

use App\Tests\Application\AbstractApplicationCase;
use PHPUnit\Framework\Attributes as PHPUnitAttr;
use App\Controller;

#[PHPUnitAttr\CoversClass(Controller\EmailController::class)]
class EmailControllerTest extends AbstractApplicationCase
{
	#[PHPUnitAttr\Before]
	public function before(): void {}
	
	#[PHPUnitAttr\After]
	public function after(): void {}
	
	public function testOneMessageSentWithAsyncTransport() {
		$client = static::createClient();
		$client->followRedirects(false);
		$crawler = $client->request('GET', '/email/send');
		$client->followRedirect();
		
		$this->assertResponseIsSuccessful();
		
		$event = $this->getMailerEvent(0);
		$message = $this->getMailerMessage(0);

		$this->assertEmailCount(1);
		//$this->assertQueuedEmailCount(1);
		
		$this->assertEmailIsNotQueued($event);

		$this->assertEmailHasHeader($message, 'To');
		$this->assertEmailHasHeader($message, 'From');
		$this->assertEmailHasHeader($message, 'Subject');
		$this->assertEmailAddressContains($message, 'From', 'alex@woodenalex.ru');
		$this->assertEmailHeaderSame($message, 'To', 'alex@woodenalex.ru');
		$this->assertEmailSubjectContains($message, '[TEST]');
		
		$this->assertEmailTextBodyContains($message, 'TEST');
		$this->assertEmailHtmlBodyContains($message, 'TEST');
		
		$this->assertEmailAttachmentCount($message, 2);
	}
}