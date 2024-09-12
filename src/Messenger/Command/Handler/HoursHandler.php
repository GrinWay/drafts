<?php

namespace App\Messenger\Command\Handler;

use App\Messenger\Command\Message\Hours;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use GrinWay\WebApp\Type\Messenger\BusTypes;
use App\Service\CarbonService;
use App\Messenger\AbstractHandler;

#[AsMessageHandler(
    bus: BusTypes::QUERY_BUS,
)]
class HoursHandler extends AbstractHandler
{
    public function __construct()
    {
    }

    public function __invoke(Hours $message)
    {
        return CarbonService::get(
            carbonStart: static fn($c) => $c->startOfMonth(),
            carbonEnd: static fn($c) => $c->endOfDay(),
            onlyCarbonProperty: 'hour',
            includePassed: $message->includePassed
        );
    }
}
