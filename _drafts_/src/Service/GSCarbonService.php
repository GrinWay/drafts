<?php

namespace GrinWay\GenericParts\Service;

use Carbon\{
    Carbon,
    CarbonImmutable
};

use function Symfony\Component\String\u;

use GrinWay\GenericParts\Contracts\{
    GrinWayIsoFormat
};
use GrinWay\GenericParts\IsoFormat\{
    GrinWayLLLIsoFormat
};

class GrinWayCarbonService
{
    public static function isoFormat(
        Carbon|CarbonImmutable $carbon,
        ?GrinWayIsoFormat $isoFormat = null,
        bool $isTitle = true,
    ): string {
        $isoFormat  ??= new GrinWayLLLIsoFormat();
        $tz         = $carbon->tz;

        return (string) u($carbon->isoFormat($isoFormat::get()) . ' [' . $tz . ']')->title($isTitle);
    }

    public static function forUser(
        Carbon|CarbonImmutable $origin,
        \DateTimeImmutable|\DateTime $sourceOfMeta = null,
        ?string $tz = null,
        ?string $locale = null,
    ): Carbon|CarbonImmutable {
        $carbonClone            = ($origin instanceof Carbon) ? $origin->clone() : $origin;
        return $sourceOfMeta ?
            $carbonClone->tz($sourceOfMeta->tz)->locale($sourceOfMeta->locale) :
            $carbonClone->tz($tz ?? $carbonClone->tz)->locale($locale ?? $carbonClone->locale)
        ;
    }
}
