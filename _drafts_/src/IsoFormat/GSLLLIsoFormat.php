<?php

namespace GrinWay\GenericParts\IsoFormat;

use GrinWay\GenericParts\Contracts\GrinWayIsoFormat;

class GrinWayLLLIsoFormat implements GrinWayIsoFormat
{
    public static function get(): string
    {
        return 'dddd, MMMM D, YYYY h:mm:ss A';
    }
}
