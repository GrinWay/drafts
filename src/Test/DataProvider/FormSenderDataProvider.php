<?php

namespace App\Test\DataProvider;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use App\Decorator\Carbon\CarbonImmutableDecorator;

class FormSenderDataProvider
{
    public static function senders(): array
    {
        return [
		
            'First client sends the form' => [ // ERROR (explained below)
				'client1',
				'crawler1', // <- The form will be chosen by the client's crawler
			],
			
            'Second client sends the form' => [ // OK
				'client2',
				'crawler2', // <- The form will be chosen by the client's crawler
			],
			
			/* Incorrect by meaning examples (can't imagine this is possible in our real life):

            'First client sends the second browser\'s form' => [ // OK (HOW IS IT POSSIBLE AT ALL?!)
				'client1',
				'crawler2', // <- The form will be chosen by the client's crawler
			],

            'Second client sends the first browser\'s form' => [ // ERROR (explained below)
				'client2',
				'crawler1', // <- The form will be chosen by the client's crawler
			],

			*/
			
			/* First client sends the form ENDS WITH:
			Facebook\WebDriver\Exception\ElementClickInterceptedException: element click intercepted: Element <button class="btn btn-large btn-outline-primary">...</button> is not clickable at point (63, 632). Other element would receive the click: <svg xmlns="http://www.w3.org/2000/svg" data-icon-name="icon-tabler-chart-infographic" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" role="img">...</svg>
		   ├   (Session info: chrome=129.0.6668.70)
		   │
		   │ C:\Workspace\SYMFONY\__SANDBOX__\drafts\vendor\php-webdriver\webdriver\lib\Exception\WebDriverException.php:96
		   │ C:\Workspace\SYMFONY\__SANDBOX__\drafts\vendor\php-webdriver\webdriver\lib\Remote\HttpCommandExecutor.php:359
		   │ C:\Workspace\SYMFONY\__SANDBOX__\drafts\vendor\php-webdriver\webdriver\lib\Remote\RemoteWebDriver.php:601
		   │ C:\Workspace\SYMFONY\__SANDBOX__\drafts\vendor\php-webdriver\webdriver\lib\Remote\RemoteExecuteMethod.php:23
		   │ C:\Workspace\SYMFONY\__SANDBOX__\drafts\vendor\php-webdriver\webdriver\lib\Remote\RemoteWebElement.php:78
		   │ C:\Workspace\SYMFONY\__SANDBOX__\drafts\vendor\symfony\panther\src\Client.php:241
		   │ C:\Workspace\SYMFONY\__SANDBOX__\drafts\tests\E2E\Controller\ChatControllerTest.php:45
			*/
			
			/* Second client sends the first browser\'s form ENDS WITH:
			Facebook\WebDriver\Exception\ElementClickInterceptedException: element click intercepted: Element <button class="btn btn-large btn-outline-primary">...</button> is not clickable at point (63, 633). Other element would receive the click: <svg xmlns="http://www.w3.org/2000/svg" data-icon-name="icon-tabler-chart-infographic" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" role="img">...</svg>
		   ├   (Session info: chrome=129.0.6668.70)
		   │
		   │ C:\Workspace\SYMFONY\__SANDBOX__\drafts\vendor\php-webdriver\webdriver\lib\Exception\WebDriverException.php:96
		   │ C:\Workspace\SYMFONY\__SANDBOX__\drafts\vendor\php-webdriver\webdriver\lib\Remote\HttpCommandExecutor.php:359
		   │ C:\Workspace\SYMFONY\__SANDBOX__\drafts\vendor\php-webdriver\webdriver\lib\Remote\RemoteWebDriver.php:601
		   │ C:\Workspace\SYMFONY\__SANDBOX__\drafts\vendor\php-webdriver\webdriver\lib\Remote\RemoteExecuteMethod.php:23
		   │ C:\Workspace\SYMFONY\__SANDBOX__\drafts\vendor\php-webdriver\webdriver\lib\Remote\RemoteWebElement.php:78
		   │ C:\Workspace\SYMFONY\__SANDBOX__\drafts\vendor\symfony\panther\src\Client.php:241
		   │ C:\Workspace\SYMFONY\__SANDBOX__\drafts\tests\E2E\Controller\ChatControllerTest.php:45
			*/
        ];
    }
}
