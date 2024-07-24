<?php

namespace App\Security\ExpressionLanguage;

use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;

class SecurityExpressionLanguageProvider implements ExpressionFunctionProviderInterface {
	public function getFunctions(): array
    {
        return [
			/*
			*/
            new ExpressionFunction(
				'get',
				static function ($args): mixed {
					return \sprintf('%s', $args);
				},
				static function ($vars, $args): mixed {
					\dd($vars, $args);
				}
			),
        ];
    }
}