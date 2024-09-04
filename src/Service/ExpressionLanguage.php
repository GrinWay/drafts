<?php

namespace App\Service;

use Symfony\Component\Cache\Adapter\Psr16Adapter;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage as BaseExpressionLanguage;
use App\Extension\ExpressionLanguage\ExpressionLanguageFunctionProvider;

class ExpressionLanguage extends BaseExpressionLanguage
{
    public function __construct(?CacheItemPoolInterface $cache = null, array $providers = [], #[Autowire('@app.cache_adapter.php_files')] $cachePool = null)
    {
		if (null !== $cachePool) {
			$cache = $cachePool;
		}
		
        // prepends provider to let users add functions
        \array_unshift($providers, new ExpressionLanguageFunctionProvider());

        parent::__construct($cache, $providers);
    }
}
