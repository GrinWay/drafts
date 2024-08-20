<?php

namespace App\Trait\ServiceLocator\Aware;

use Symfony\Contracts\Service\Attribute\SubscribedService;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Faker\Generator;

trait FakerAware
{
    #[SubscribedService(
        attributes: [
            new Autowire('@grin_way_service.faker'),
        ]
    )]
    protected function faker(): Generator
    {
        return $this->container->get(__CLASS__ . '::' . __FUNCTION__);
    }
}
