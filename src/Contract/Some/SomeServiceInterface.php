<?php

namespace App\Contract\Some;

interface SomeServiceInterface
{
    public function getGenerator(...$args): \Generator;
}
