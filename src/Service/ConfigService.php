<?php

namespace App\Service;

use GrinWay\Service\Service\ConfigService as GrinWayConfigService;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;

class ConfigService extends GrinWayConfigService
{
    public function __construct(
        BoolService $boolService,
        StringService $stringService,
        #[Autowire(param: 'kernel.project_dir')]
        string $grinWayServiceProjectDir,
        #[Autowire(param: 'grin_way_service.load_packs_configs')]
        array $grinWayServicePackageFilenames,
        #[Autowire(service: 'Symfony\Contracts\Translation\TranslatorInterface')]
        $grinWayServiceT,
    ) {
        parent::__construct(
            boolService: $boolService,
            stringService: $stringService,
            grinWayServiceProjectDir: $grinWayServiceProjectDir,
            grinWayServicePackageFilenames: $grinWayServicePackageFilenames,
            grinWayServiceT: $grinWayServiceT,
        );
    }


    //###> ABSTRACT REALIZATION ###

    protected function configureConfigOptions(
        string $uniqPackId,
        OptionsResolver $resolver,
        array $inputData,
    ): void {

        /*
        if ($this->getUniqPackId('', 'config/packages') == $uniqPackId) {
            return;
        }
        */

        parent::configureConfigOptions($uniqPackId, $resolver, $inputData);
    }

    //###< ABSTRACT REALIZATION ###


    //###> HELPER ###

    //###< HELPER ###
}
