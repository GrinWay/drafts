<?php

namespace App\Test\DataProvider;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use App\Decorator\Carbon\CarbonImmutableDecorator;

class CarbonProvider
{
    public static function carbonAndCarbonImmutable(): array
    {
        return [
            'CarbonImmutable' => [CarbonImmutable::class],
            /*
            'Carbon' => [Carbon::class],
            'CarbonImmutableDecorator' => [CarbonImmutableDecorator::class],
            */
        ];
    }
}
