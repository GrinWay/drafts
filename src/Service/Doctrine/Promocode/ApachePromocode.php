<?php

namespace App\Service\Doctrine\Promocode;

use App\Contract\Doctrine\Promocode\PromocodeInterface;

class ApachePromocode implements PromocodeInterface
{
    public function getDiscount(): string
    {
        return 100;
    }

    public static function getIndex(): string
    {
        return 'APACHE';
    }
}
