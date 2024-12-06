<?php

namespace App\Tests\Controller;

use App\Controller\HomeController;
use App\Test\DataProvider\HomeControllerDataProvider;
use App\Tests\InitTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use Symfony\Component\Panther\Client;
use Symfony\Component\Panther\PantherTestCase;

class HomeControllerTest extends PantherTestCase
{
//    use InitTrait;

    public function testHomeTitleText()
    {
        $client = static::createPantherClient();

        $crawler = $client->request('GET', '/');

        $this->assertSelectorWillExist('title');
    }
}
