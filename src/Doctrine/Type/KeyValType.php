<?php

namespace App\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Types\Types;

class KeyValType extends Type
{
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getStringTypeDeclarationSQL($column);
    }

    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): string
    {
        if (\is_string($value)) {
            return $value;
        }

        if (\is_array($value)) {
            return \implode(',', $value);
        }

        throw new \Exception('Incorrect type');
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): string|array
    {
        if (!\is_string($value)) {
            throw new \Exception('Incorrect type');
        }
        $keyValue = \explode(',', $value);

        $key = \array_shift($keyValue);
        $value = \array_pop($keyValue);

        if ($value === null) {
            return $key;
        }

        return [$key => $value];
    }
}
