<?php

namespace App\Tests\Unit;

use PHPUnit\Framework\Attributes\CoversNothing;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\HttpClient\Retry\RetryStrategyInterface;
use Symfony\Component\HttpClient\RetryableHttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[CoversNothing]
class HttpClientTest extends KernelTestCase
{
    public function testRetryableHttpClient()
    {
        $container = self::getContainer();
        $env = $container->get('container.getenv');

        $responseFactory = [
            new MockResponse(info: ['http_code' => 423]),
            new MockResponse(info: ['http_code' => 423]),
            new MockResponse(info: ['http_code' => 200]),
        ];
        $mockHttpClient = new MockHttpClient($responseFactory);

        $mockRetryStrategy = $this->createMock(RetryStrategyInterface::class);
        $mockRetryStrategy
            ->expects(self::atLeastOnce())
            ->method('getDelay')
            ->willReturn(0)
        ;
        $mockRetryStrategy
            ->expects(self::atLeastOnce())
            ->method('shouldRetry')
            ->willReturn(true)
        ;
        $client = new RetryableHttpClient(
            $mockHttpClient,
            strategy: $mockRetryStrategy,
            maxRetries: 2,
        );
        $response = $client->request('GET', '/', [
            'base_uri' => $env('resolve:APP_URL'),
        ]);

        $this->assertSame(200, $response->getStatusCode());
    }
}
