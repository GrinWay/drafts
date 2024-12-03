<?php

namespace App\Security\ExpressionLanguage;

use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('security.expression_language_provider')]
class SecurityExpressionLanguageProvider implements ExpressionFunctionProviderInterface
{
    public function getFunctions(): array
    {
        return [
			new ExpressionFunction(
				'is_object',
				static function (...$args): mixed {
					return \sprintf('\\is_object(%s);', \array_shift($args));
				},
				static function ($vars, ...$args): mixed {
					return \is_object(\array_shift($args));
				},
			),
		];
        return [
            /*
            */
            new ExpressionFunction(
                'get',
                static function ($args): mixed {
                    return \sprintf('%s', $args);
                },
                function ($vars, $args): mixed {
                    return $args;
                }
            ),
        ];
    }
}
