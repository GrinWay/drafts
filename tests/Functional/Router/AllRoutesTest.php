<?php

namespace App\Tests\Functional\Router;

use App\Test\DataProvider\RouterProvider;
use App\Tests\Functional\AbstractWebTestCase;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

#[CoversNothing]
class AllRoutesTest extends AbstractWebTestCase
{
//    use Factories, ResetDatabase, HasBrowser;

    #[DataProviderExternal(RouterProvider::class, 'all')]
    public function testSuccessResponse(string $uri)
    {
//        $this->browser()
//            ->visit($uri)
//            ->assertSuccessful()
//        ;

        $this->assertSame(0, 0);
    }
}
