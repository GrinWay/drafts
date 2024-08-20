<?php

namespace App\Messenger\Command\Handler;

use App\Messenger\Command\Message\OnlyWeekendsOfThisMonth;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use GrinWay\WebApp\Type\Messenger\BusTypes;
use App\Service\CarbonService;
use App\Messenger\AbstractHandler;

#[AsMessageHandler(
    bus: BusTypes::QUERY_BUS,
)]
class OnlyWeekendsOfThisMonthHandler extends AbstractHandler
{
    public function __construct(
	) {}
	
    public function __invoke(OnlyWeekendsOfThisMonth $message) {
		return CarbonService::getWeekends(
			carbonStart: static fn($c) => $c->startOfMonth(),
			carbonEnd: static fn($c) => $c->endOfMonth(),
			onlyCarbonProperty: 'day',
			includePassed: $message->includePassed
		);
	}
}
