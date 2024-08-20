<?php

namespace App\EnvProcessor;

use Symfony\Component\DependencyInjection\EnvVarProcessorInterface;

abstract class AbstractEnvProcessor implements EnvVarProcessorInterface
{
    public function getEnv(
        string $prefix,
        string $name,
        \Closure $getEnv,
    ): mixed {
        return $this->get($getEnv($name));
    }

    public static function getProvidedTypes(): array
    {
        return [
            static::getName() => \implode('|', static::getTypes()),
        ];
    }


    abstract protected static function getName(): string;

    abstract protected static function getTypes(): array;

    abstract protected function get(
        mixed $env,
    ): mixed;
}
