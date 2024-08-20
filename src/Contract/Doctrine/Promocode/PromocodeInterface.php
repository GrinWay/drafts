<?php

namespace App\Contract\Doctrine\Promocode;

interface PromocodeInterface
{
    public function getDiscount(): string;

    public static function getIndex(): string;
}
