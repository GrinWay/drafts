<?php

namespace App\Tests\Unit\Security;

use App\Tests\Unit\HttpClientTest;
use PHPUnit\Framework\Attributes\CoversNothing;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Contracts\Cache\ItemInterface;

#[CoversNothing]
class ArrayCachePoolAdapterTest extends HttpClientTest
{
    public function testArrayCache()
    {
        $container = self::getContainer();
        $arrayCache = $container->get(CacheItemPoolInterface::class . ' $appCacheTest');

        $i = 0;
        $cachedValueGetter = static function () use ($arrayCache, $i) {
            return $arrayCache->get('test-value', static function (ItemInterface $item) use ($i) {
                return $i++;
            });
        };

        $result_1 = $cachedValueGetter();
        $result_2 = $cachedValueGetter();

        $this->assertSame($result_1, $result_2);
    }
}
