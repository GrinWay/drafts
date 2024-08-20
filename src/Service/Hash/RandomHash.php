<?php

namespace App\Service\Hash;

use App\Type\Hash\HashType;
use App\Attribute\NewClosureDefinitionWithTag;

class RandomHash
{
    #[NewClosureDefinitionWithTag(HashType::TAG, 'random')]
    public function dsklfjsdlf(): string
    {
        return md5(rand(0, 10_000));
    }
}
