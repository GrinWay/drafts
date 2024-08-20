<?php

namespace App\Doctrine\Type;

use Doctrine\DBAL\Types\Exception\InvalidType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Carbon\Doctrine\DateTimeImmutableType as CarbonDateTimeImmutableType;
use Doctrine\DBAL\Types\DateTimeImmutableType as DoctrineDateTimeImmutableType;

class DateTimeImmutableType extends CarbonDateTimeImmutableType
{
	public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value === null) {
            return $value;
        }

        if ($value instanceof \DateTimeInterface) {
            return $value->format('Y-m-d H:i:s.u');
        }

        throw InvalidType::new(
            $value,
            static::class,
            ['null', 'DateTime', 'Carbon']
        );
    }
}
