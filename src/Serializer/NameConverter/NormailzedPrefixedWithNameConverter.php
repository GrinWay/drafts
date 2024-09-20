<?php

namespace App\Serializer\NameConverter;

use Symfony\Component\Serializer\NameConverter\NameConverterInterface;

class NormailzedPrefixedWithNameConverter implements NameConverterInterface
{
	public function __construct(
		private readonly string $prefix,
	) {}
	
	public function normalize(string $propertyName): string
    {
        return $this->prefix.$propertyName;
    }

    public function denormalize(string $propertyName): string
    {
		$prefix = $this->prefix;
		$propertyNameWithoutPrefix = static fn() => \substr($propertyName, \mb_strlen($prefix));
        return \str_starts_with($propertyName, $prefix) ? $propertyNameWithoutPrefix() : $propertyName;
    }
}