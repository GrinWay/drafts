<?php

namespace App\Kernel\CacheCleaner;

use Symfony\Component\HttpKernel\CacheClearer\CacheClearerInterface;

class DefaultCacheCleaner implements CacheClearerInterface
{
	public function clear(string $cacheDirectory): void
    {
        // clear your cache
    }
}