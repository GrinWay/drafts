<?php

namespace App\Service;

use App\Contract\Doctrine\Promocode\PromocodeInterface;

class IpUtils implements PromocodeInterface
{
    public function __construct(
        private readonly OSService $oSService,
    ) {
    }


    public function getDiscount(): string
    {
        return '';
    }

    public static function getIndex(): string
    {
        return '';
    }
}
