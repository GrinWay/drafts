<?php

namespace App\Messenger\Command\Handler;

use Symfony\Component\Messenger\Exception\RecoverableMessageHandlingException;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\Messenger\Exception\StopWorkerException;
use App\Messenger\Command\Message\HowStampWorks;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use GrinWay\WebApp\Type\Messenger\BusTypes;
use App\Service\CarbonService;
use App\Messenger\AbstractHandler;

#[AsMessageHandler(
    //bus: BusTypes::QUERY_BUS,
	priority: 1,
)]
class HowStampWorks2Handler extends AbstractHandler
{
    public function __construct(
	) {}
	
    public function __invoke(HowStampWorks $message) {
		$response = 'HANDLED 2';
		\dump($response);
		return $response;
	}
}
