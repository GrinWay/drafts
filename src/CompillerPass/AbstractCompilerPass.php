<?php

namespace App\CompillerPass;

use App\Type\Hash\HashType;
use App\Messenger\MessengerManager;
use Symfony\Component\DependencyInjection\Reference;

use function Symfony\component\string\u;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use App\Extractor\DefaultValueExtractor;
use Symfony\Component\DependencyInjection\Argument\ServiceClosureArgument;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Alias;

abstract class AbstractCompilerPass implements CompilerPassInterface
{
    /**
    * Usage:
    *    $index = self::getFirstTagAttribute($index, $originTagAttributes);
    */
    protected static function getFirstTagAttribute(string|int|null $key, array $tagAttributes, bool $throw = false): mixed
    {
        if (null === $key) {
            return null;
        }

        foreach ($tagAttributes as $tagAttribute) {
            if (isset($tagAttribute[$key])) {
                return $tagAttribute[$key];
            }
        }
        if (true === $throw) {
            throw new \Exception(
                \sprintf(
                    'There wasn\'t at least one key: "%s" among values: "%s" in method "%s"',
                    $key,
                    \implode(', ', $tagAttribute),
                    __FUNCTION__,
                )
            );
        }
        return null;
    }
}
