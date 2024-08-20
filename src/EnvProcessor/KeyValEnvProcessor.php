<?php

namespace App\EnvProcessor;

class KeyValEnvProcessor extends AbstractEnvProcessor
{
    //###> ABSTRACT REALIZATION ###

    protected static function getName(): string
    {
        return 'key_val';
    }

    protected static function getTypes(): array
    {
        return [
            \get_debug_type(''),
        ];
    }

    protected function get(
        mixed $env,
    ): mixed {
        if (\is_array($env) && \count($env) >= 2) {
            $env = [\array_shift($env) => \array_pop($env)];
        }

        return $env;
    }

    //###< ABSTRACT REALIZATION ###
}
