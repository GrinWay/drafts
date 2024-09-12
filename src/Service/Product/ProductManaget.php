<?php

namespace App\Service\Product;

use App\Entity\Product;
use App\Type\Product\ProductTypes;

use function Symfony\Component\String\u;

class ProductManaget
{
    public static function createFromType(string $type, array $args = [], bool $throw = true): ?Product
    {
        $productTypes = (new \ReflectionClass(ProductTypes::class))->getConstants();

        foreach ($productTypes as $constName => $definedType) {
            if ($type === $definedType) {
                $className = u(\strtolower($constName))->title() . 'Product';
                return new $className(...$args);
            }
        }

        if (true === $throw) {
            throw new \Exception(\sprintf(
                'Can\'t create a certain "%s" instance by type name: "%s", available values are: "%s".',
                Product::class,
                $type,
                \implode(', ', $productTypes),
            ));
        }

        return null;
    }
}
