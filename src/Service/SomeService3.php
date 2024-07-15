<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\Attribute\AsAlias;
use Psr\Log\LoggerInterface;

class SomeService3 extends AbstractStringService
{
    public function getGenerator(...$args): \Generator
    {
        yield ['data' => 0];
    }
}
