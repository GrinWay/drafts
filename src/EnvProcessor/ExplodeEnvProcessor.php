<?php

namespace App\EnvProcessor;

use GrinWay\EnvProcessor\DependencyInjection\AbstractWithParamsVarProcessor;

class ExplodeEnvProcessor extends AbstractWithParamsVarProcessor
{
    public const DEFAULT_SEPARATOR = ',';

    public const ENV_PROCESSOR_TYPES = [
        'string',
    ];

    public static function getEnvProcessorName(): string
    {
        return 'explode';
    }

    //###> ABSTRACT REALIZATION ###

    protected function get(
        string $prefix,
        string $nameWithoutParameters,
        \Closure $getEnv,
        array $parameters,
    ): mixed {
        $env = $getEnv($nameWithoutParameters);

        $separator = \count($parameters) > 0 ? \array_shift($parameters) : self::DEFAULT_SEPARATOR;
        $separator ??= self::DEFAULT_SEPARATOR;

        return \explode($separator, $env);
    }

    //###< ABSTRACT REALIZATION ###
}
