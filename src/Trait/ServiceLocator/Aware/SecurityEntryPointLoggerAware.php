<?php

namespace App\Trait\ServiceLocator\Aware;

use Symfony\Contracts\Service\Attribute\SubscribedService;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

trait SecurityEntryPointLoggerAware
{
    #[SubscribedService(
        attributes: [
            new Autowire('@monolog.logger.security.entry_point'),
        ]
    )]
    protected function entryPointLogger(): mixed
    {
        return $this->container->get(__CLASS__ . '::' . __FUNCTION__);
    }
}
