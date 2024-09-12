<?php

namespace App\Extension\ExpressionLanguage;

use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;
use Symfony\Component\ExpressionLanguage\ExpressionFunction;

//TODO: ExpressionLanguageStaticFunctions
class ExpressionLanguageStaticFunctions
{
    //###> IS_INTEGER ###
    public static function getIsIntegerFunc(string $name = 'is_integer'): ExpressionFunction
    {
        return new ExpressionFunction(
            $name,
            static fn(...$a) => [self::class, 'getIsIntegerFuncCompiled'](...$a),
            static fn(...$va) => [self::class, 'getIsIntegerFuncEvaluated'](\array_shift($va), ...$va),
        );
    }

    public static function getIsIntegerFuncCompiled(...$arguments): string
    {
        return \sprintf("\\is_integer(%s)", \array_shift($arguments));
    }

    public static function getIsIntegerFuncEvaluated(array $variables, ...$arguments): bool
    {
        return \is_integer(\array_shift($arguments));
    }
    //###< IS_INTEGER ###


    //###> IS_ARRAY ###
    public static function getIsArrayFunc(string $name = 'is_array'): ExpressionFunction
    {
        return new ExpressionFunction(
            $name,
            static fn(...$a) => [self::class, 'getIsArrayFuncCompiled'](...$a),
            static fn(...$va) => [self::class, 'getIsArrayFuncEvaluated'](\array_shift($va), ...$va),
        );
    }

    public static function getIsArrayFuncCompiled(...$arguments): string
    {
        return \sprintf("\\is_array(%s)", \array_shift($arguments));
    }

    public static function getIsArrayFuncEvaluated(array $variables, ...$arguments): bool
    {
        return \is_array(\array_shift($arguments));
    }
    //###< IS_ARRAY ###
}
