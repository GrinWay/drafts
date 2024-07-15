<?php

namespace GrinWay\GenericParts\Twig\Extension;

use Twig\Extension\AbstractExtension;

class ByteForHumansExtension extends AbstractExtension
{
    //###> FILTERS ###

    public function getFilters()
    {
        return [
            new \Twig\TwigFilter('grin_way_byte', $this->format_bytes(...)),
        ];
    }

    public function format_bytes(
        string|int $bytes,
        $si = false,
    ) {
        $unit = $si ? 1000 : 1024;
        if ($bytes <= $unit) {
            return $bytes . " B";
        }
        $pre = ($si ? "kMGTPE" : "KMGTPE");
        $count = \count(\str_split($pre));
        $exp = (int)(\log($bytes) / \log($unit));
        $exp = $exp > $count ? $count : $exp;
        //\dd($exp, $maxIndex);
        $pre = $pre[$exp - 1]; //$pre = $pre[$exp - 1] . ($si ? "" : "i");
        return \sprintf("%.1f %sB", $bytes / \pow($unit, $exp), $pre);
    }

    //###< FILTERS ###
}
