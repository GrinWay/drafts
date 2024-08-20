<?php

namespace App\Attribute\Twig;

#[\Attribute(\Attribute::TARGET_METHOD)]
class MyTemplate
{
    public function __construct(
        public readonly string $path,
    ) {
    }
}
