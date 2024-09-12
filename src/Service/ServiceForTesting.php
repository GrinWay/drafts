<?php

namespace App\Service;

use App\Service\StringService;

class ServiceForTesting
{
    public function __construct(
        private readonly StringService $stringService,
    ) {
    }

    public function getPassedString(string $string): string
    {
        return $string;
    }

    public function getFilenameWithExt(string $string, string $ext): string
    {
        $r = $this->stringService->getFilenameWithExt($string, $ext);
        $r = \trim($r, " \n\r\t\v\x00");
        return $r;
    }
}
