<?php

namespace App\Service;

use GrinWay\Service\Service\FilesystemService as GrinWayFilesystemService;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\String\Slugger\SluggerInterface;

class FilesystemService extends GrinWayFilesystemService
{
    public function __construct(
        DumpInfoService $dumpInfoService,
        StringService $stringService,
        SluggerInterface $slugger,
        #[Autowire(param: 'grin_way_service.local_drive_for_test')]
        string $grinWayServiceLocalDriveForTest,
        #[Autowire(param: 'grin_way_service.app_env')]
        string $grinWayServiceAppEnv,
        #[Autowire(service: 'grin_way_service.carbon_factory_immutable')]
        $grinWayServiceCarbonFactoryImmutable,
    ) {
        parent::__construct(
            dumpInfoService: $dumpInfoService,
            stringService: $stringService,
            slugger: $slugger,
            grinWayServiceLocalDriveForTest: $grinWayServiceLocalDriveForTest,
            grinWayServiceAppEnv: $grinWayServiceAppEnv,
            grinWayServiceCarbonFactoryImmutable: $grinWayServiceCarbonFactoryImmutable,
        );
    }
}
