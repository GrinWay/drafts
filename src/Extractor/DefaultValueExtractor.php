<?php

namespace App\Extractor;

use Symfony\Component\PropertyInfo\PropertyTypeExtractorInterface;

class DefaultValueExtractor// implements PropertyTypeExtractorInterface
{
    public function getTypes(
        string $class,
        string $property,
        array $context = [],
    ): ?array {
        $refl = new \ReflectionProperty($class, $property);
        $doc = $refl->getDocComment();
        if ($doc === false) {
            return null;
        }

        $matches = [];
        $match = null;
        \preg_match('~=(?<expr>.+);?\s*$~Um', $doc, $matches);
        if (isset($matches['expr'])) {
            $match = [\trim($matches['expr'])];
        }

        return $match;
    }
}
