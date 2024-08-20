<?php

namespace App\Service\Carbon;

use Carbon\Carbon;
use Carbon\CarbonImmutable;

class DateTimeFormat
{
    public function __construct()
    {
    }

    public function getFormat(): string
    {
        return 'd.m.Y H:i:s P';
    }

    public function __invoke(Carbon|CarbonImmutable $carbon): string
    {
        return $carbon->format($this->getFormat());
    }
}
