<?php

namespace App\Trait\ServiceLocator\Aware;

use Psr\Log\LoggerInterface;
use Symfony\Contracts\Service\Attribute\SubscribedService;
use Symfony\Component\DependencyInjection\Attribute\Target;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

trait LoggerAware
{
    #[SubscribedService(
        attributes: [
            //new Target('$messengerEmailLogger'),
            new Autowire('@' . LoggerInterface::class . ' $messengerEmailLogger'),
        ]
    )]
    protected function logger(): LoggerInterface
    {
        return $this->container->get(__CLASS__ . '::' . __FUNCTION__);
    }
}
