<?php

namespace GrinWay\GenericParts\Messenger\KernelBootstrap\Command;

class SetDefaultDateTimeZone
{
    public function __construct(
        private string $tz = 'UTC',
    ) {
    }

    public function getTZ()
    {
        return $this->tz;
    }
}
