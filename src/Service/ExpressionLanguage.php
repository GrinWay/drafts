<?php

namespace App\Service;

use Symfony\Component\ExpressionLanguage\ExpressionLanguage as BaseExpressionLanguage;
use App\Extension\ExpressionLanguage\ExpressionLanguageFunctionProvider;

class ExpressionLanguage extends BaseExpressionLanguage
{
    public function __construct(?CacheItemPoolInterface $cache = null, array $providers = [])
    {
        // prepends provider to let users add functions
        \array_unshift($providers, new ExpressionLanguageFunctionProvider());

        parent::__construct($cache, $providers);
    }
}
