<?php

namespace App\Messenger\Command\Handler;

use App\Messenger\Command\Message\SecurityAlwaysRememberMe;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use GrinWay\WebApp\Type\Messenger\BusTypes;
use App\Service\CarbonService;
use App\Messenger\AbstractHandler;
use App\Service\ConfigService;

#[AsMessageHandler(
    bus: BusTypes::QUERY_BUS,
)]
class SecurityAlwaysRememberMeHandler extends AbstractHandler
{
    public function __construct(
        private readonly ConfigService $configService,
    ) {
    }

    public function __invoke(SecurityAlwaysRememberMe $message)
    {
        return $this->configService->getPackageValue(
            packName:                   'security.yaml',
            propertyAccessString:       '[security][firewalls][main][remember_me][always_remember_me]',
            packRelPath:                'config/packages',
        );
    }
}
