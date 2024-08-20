<?php

namespace App\Contract;

interface SomeInterface
{
    public function getPath(
        string ...$parts,
    ): string;
}
