<?php

namespace App\QrCode\Label\Font;

use Endroid\QrCode\Label\Font\FontInterface;

class SchadowBTRoman implements FontInterface
{
    public function __construct(
        private int $size = 16
    ) {
    }

    public function getPath(): string
    {
        return __DIR__ . '/../../../../assets/styles/font/SchadowBTRoman.ttf';
    }

    public function getSize(): int
    {
        return $this->size;
    }
}
