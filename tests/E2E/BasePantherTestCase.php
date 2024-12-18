<?php

namespace App\Tests\E2E;

use Symfony\Component\Panther\PantherTestCase;
use Zenstruck\Browser\KernelBrowser;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class BasePantherTestCase extends PantherTestCase
{
    use ResetDatabase, Factories, HasBrowser {
        browser as _browser;
    }

    protected function browser(array $options = [], array $server = []): KernelBrowser
    {
        return $this->_browser($options, $server)
            ->catchExceptions();
    }
}
