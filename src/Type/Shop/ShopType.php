<?php

namespace App\Type\Shop;

enum ShopType: string
{
    case FOOD                   = 'food';
    case MACHINE                = 'mach';
    case ELECTRONIC             = 'el';
}
