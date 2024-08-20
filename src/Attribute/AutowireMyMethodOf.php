<?php

namespace App\Attribute;

#[\Attribute(\Attribute::TARGET_PARAMETER)]
class AutowireMyMethodOf
{
    public function __construct(
        public readonly ?string $id = null,
    ) {
    }
}
