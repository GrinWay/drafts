<?php

namespace App\Tests\Unit;

use PHPUnit\Framework\Attributes\CoversNothing;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[CoversNothing]
class HttpClientTest extends KernelTestCase
{
    public function testThisHttpClient()
    {
        $container = self::getContainer();

        $client = $container->get(HttpClientInterface::class . ' $thisClient');
        $response = $client->request('GET', '/');

        $this->assertSame(200, $response->getStatusCode());
    }
}
