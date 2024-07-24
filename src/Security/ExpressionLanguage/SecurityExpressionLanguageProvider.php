<?php

namespace App\Security\ExpressionLanguage;

use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;

class SecurityExpressionLanguageProvider implements ExpressionFunctionProviderInterface {
	public function getFunctions(): array
    {
        return [];
        return [
			/*
			*/
            new ExpressionFunction(
				'get',
				static function ($args): mixed {
					return \sprintf('%s', $args);
				},
				function ($vars, $args): mixed {
					\dd($args);
					return $args;
				}
			),
        ];
    }
}