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
        return \str_starts_with($propertyName, $this->prefix) ? \substr($propertyName, \strpos($propertyName, $this->prefix)) : $propertyName;
    }
}