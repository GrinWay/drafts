<?php

namespace App\Router\ExpressionLanguage\ExpressionLanguageProvider;

use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;
use Symfony\Contracts\Service\ServiceProviderInterface;
use Symfony\Component\Routing\Matcher\ExpressionLanguageProvider;

class RouterExpressionLanguageProviderDecorator extends ExpressionLanguageProvider
{
    public function __construct(
        private $inner,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            ...$this->inner->getFunctions(),
            /*
            new ExpressionFunction(
                'get',
                static function ($args): mixed {
                    return \sprintf('%s', $args);
                },
                static function ($vars, $args): mixed {
                    \dd($vars, $args);
                }
            ),
            */
        ];
    }

    public function get(string $function): callable
    {
        return $this->inner->get($function);
    }
}
