<?php

namespace App\Extension\ExpressionLanguage\Decorator;

use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;
use App\Extension\ExpressionLanguage\ExpressionLanguageStaticFunctions;

class UxExpLangFunctionProvider implements ExpressionFunctionProviderInterface
{
    public function __construct(
        //private $inner,
    ) {
    }

    public function getFunctions(): array
    {
        return \array_merge(
            $this->inner->getFunctions(),
			[
				ExpressionLanguageStaticFunctions::getIsArrayFunc(),
			]
        );
    }
}
