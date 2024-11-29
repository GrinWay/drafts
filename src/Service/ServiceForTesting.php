<?php

namespace App\Service;

use App\Service\StringService;

class ServiceForTesting
{
    public function __construct(
		private readonly mixed $data = null,
    ) {
    }

    public function getPassedString(string $string): string
    {
        return $string;
    }

    public function getFilenameWithExt(string $string, string $ext): string
    {
        return '';
    }
}
