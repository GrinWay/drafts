<?php

namespace App\Trait\ServiceLocator\Aware;

use Psr\Log\LoggerInterface;
use Symfony\Contracts\Service\Attribute\SubscribedService;
use Symfony\Component\DependencyInjection\Attribute\Target;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

trait UserPasswordHasherAware
{
    #[SubscribedService(
        attributes: [
            new Autowire('@security.user_password_hasher'),
        ]
    )]
    protected function userPasswordHasher(): mixed
    {
        return $this->container->get(__CLASS__ . '::' . __FUNCTION__);
    }
}
