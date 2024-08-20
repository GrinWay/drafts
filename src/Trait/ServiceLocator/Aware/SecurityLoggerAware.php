<?php

namespace App\Trait\ServiceLocator\Aware;

use Psr\Log\LoggerInterface;
use Symfony\Contracts\Service\Attribute\SubscribedService;
use Symfony\Component\DependencyInjection\Attribute\Target;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

trait SecurityLoggerAware
{
    #[SubscribedService(
        attributes: [
            new Autowire('@monolog.logger.security'),
        ]
    )]
    protected function securityLogger(): mixed
    {
        return $this->container->get(__CLASS__ . '::' . __FUNCTION__);
    }
}
