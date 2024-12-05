<?php

namespace App\Tests\Controller;

use App\Controller\HomeController;
use App\Test\DataProvider\HomeControllerDataProvider;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use Symfony\Component\Panther\Client;
use Symfony\Component\Panther\PantherTestCase;

#[CoversClass(HomeController::class)]
class HomeControllerTest extends PantherTestCase
{
    #[DataProviderExternal(HomeControllerDataProvider::class, 'titles')]
    public function testHomeTitleContains(string $title)
    {
        $client = static::createPantherClient();
        $crawler = $client->request('GET', 'http://127.0.0.1:8000/');
//        $this->assertSelectorTextContains('title', $title, 'title not found');
    }
}
