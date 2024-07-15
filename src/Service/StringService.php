<?php

namespace App\Service;

use GrinWay\Service\Service\StringService as GrinWayStringService;
use Symfony\Component\DependencyInjection\Attribute\When;
use Symfony\Contracts\Service\Attribute\Required;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use GrinWay\Service\Service\ArrayService;
use GrinWay\Service\Service\CarbonService;
use GrinWay\Service\Service\BoolService;
use GrinWay\Service\Service\RegexService;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;

//#[When(env: 'dev')]
//#[AsAlias(StringService::class)]
class StringService extends GrinWayStringService
{
    public function __construct(
        ArrayService $arrayService,
        CarbonService $carbonService,
        BoolService $boolService,
        RegexService $regexService,
        #[Autowire(param: 'grin_way_service.year_regex')]
        string $grinWayServiceYearRegex,
        #[Autowire(param: 'grin_way_service.year_regex_full')]
        string $grinWayServiceYearRegexFull,
        #[Autowire(param: 'grin_way_service.ip_v4_regex')]
        string $grinWayServiceIpV4Regex,
        #[Autowire(param: 'grin_way_service.slash_of_ip_regex')]
        string $grinWayServiceSlashOfIpRegex,
        //
        $v = null,
    ) {
        parent::__construct(
            arrayService: $arrayService,
            carbonService: $carbonService,
            boolService: $boolService,
            regexService: $regexService,
            grinWayServiceYearRegex: $grinWayServiceYearRegex,
            grinWayServiceYearRegexFull: $grinWayServiceYearRegexFull,
            grinWayServiceIpV4Regex: $grinWayServiceIpV4Regex,
            grinWayServiceSlashOfIpRegex: $grinWayServiceSlashOfIpRegex,
        );
    }
}
