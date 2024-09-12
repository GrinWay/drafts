<?php

namespace App\Security\ExpressionLanguage;

use Symfony\Component\Security\Core\Authorization\ExpressionLanguage;

class SecurityExpressionLanguageDecorator extends ExpressionLanguage
{
    public function __construct(
        ?CacheItemPoolInterface $cache = null,
        array $providers = [],
    ) {
        array_unshift($providers, new SecurityExpressionLanguageProvider());

        parent::__construct($cache, $providers);
    }
}
