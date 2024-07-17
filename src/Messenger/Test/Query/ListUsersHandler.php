<?php

namespace App\Messenger\Test\Query;

use GrinWay\WebApp\Contract\Messenger\QueryHandlerInterface;
use GrinWay\WebApp\Contract\Messenger\MessageHasSyncTransportInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use App\Repository\UserRepository;
use GrinWay\WebApp\Type\Messenger\BusTypes;
use App\Contract\Messenger\CommandBusHandlerInterface;

#[AsMessageHandler(
    bus: BusTypes::QUERY_BUS,
)]
class ListUsersHandler
{
    public function __construct(
        protected readonly UserRepository $userRepository,
    ) {
    }

    public function __invoke(ListUsers $query): mixed
    {
        return $this->userRepository->findAll();
    }
}
