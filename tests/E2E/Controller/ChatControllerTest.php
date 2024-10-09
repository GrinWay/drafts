<?php

namespace App\Tests\E2E\Controller;

use App\Tests\E2E\AbstractE2ECase;
use PHPUnit\Framework\Attributes as PHPUnitAttr;
use App\Test\DataProvider\FormSenderDataProvider;

#[PHPUnitAttr\RunTestsInSeparateProcesses]
#[PHPUnitAttr\CoversClass(ChatController::class)]
class ChatControllerTest extends AbstractE2ECase
{
	/**
	 * All assertions apply to the 1-st browser (client1)
	 * But the form is submitted by the 2-nd browser (client2)
	 */
	//#[PHPUnitAttr\DataProviderExternal(FormSenderDataProvider::class, 'senders')]
	public function testAsyncMercureMessageWasShownForTheFirstClientWhenTheSecondSent() {
		/**
		 * DataProvider solves who will be sending the form
		 * and what crawler will be used for the form to get sent
		 */
		 /*
		$source = [
			'client1' => null,
			'crawler1' => null,
			
			'client2' => null,
			'crawler2' => null,
		];
		 */
		
		$client1 = static::createPantherClient();
		$crawler1 = $client1->request('GET', '/chat?topic-prefix=test_');
		$this->assertSelectorExists('#no-messages-yet');
		
		$client2 = static::createAdditionalPantherClient();
		$crawler2 = $client2->request('GET', '/chat?topic-prefix=test_');
		//no matter, same as abowe (cuz applied to the first oppened browser)
		//$this->assertSelectorExists('#no-messages-yet');
		
		$buttonCrawlerNode = $crawler2->selectButton('Отправить');
		$form = $buttonCrawlerNode->form();
		$form['chat_form[message]'] = 'It is a new message';
		$client2->submit($form);
		
		// Apply assertions in the primary browser
		$this->assertSelectorWillExist('.message');
		$this->assertSelectorNotExists('#no-messages-yet');
		$this->assertSelectorTextContains('.message', 'It is a new message');
	}
}