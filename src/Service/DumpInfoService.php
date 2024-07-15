<?php

namespace App\Service;

use GrinWay\Service\Service\DumpInfoService as GrinWayDumpInfoService;
use Symfony\Contracts\Translation\TranslatorInterface;

class DumpInfoService extends GrinWayDumpInfoService
{
    public function __construct(
        StringService $stringService,
        TranslatorInterface $grinWayServiceT,
        ConfigService $configService,
    ) {
        parent::__construct(
            stringService: $stringService,
            grinWayServiceT: $grinWayServiceT,
            configService: $configService,
        );
    }
}
