<?php

namespace App\Service\Hash;

use App\Type\Hash\HashType;
use App\Attribute\NewClosureDefinitionWithTag;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;

class ByStringHash
{
    #[NewClosureDefinitionWithTag(HashType::TAG, 'by_string')]
    public function myMethod(string $string): string
    {
        return md5($string);
    }
}
