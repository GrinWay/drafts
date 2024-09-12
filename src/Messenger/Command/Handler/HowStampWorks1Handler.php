<?php

namespace App\Messenger\Command\Handler;

use App\Messenger\Stamp\StopPropagationStamp;
use Symfony\Component\Messenger\MessageBusInterface;
use App\Messenger\Command\Message\HowStampWorks;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use GrinWay\WebApp\Type\Messenger\BusTypes;
use App\Service\CarbonService;
use App\Messenger\AbstractHandler;
use App\Repository\UserRepository;
use App\Messenger\Event\Message\TestUserWasCreated;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\Messenger\Stamp\DispatchAfterCurrentBusStamp;

#[AsMessageHandler(
    //bus: BusTypes::QUERY_BUS,
    priority: 2,
)]
class HowStampWorks1Handler extends AbstractHandler
{
    public function __construct(
        private readonly UserRepository $userRepo,
        private readonly MessageBusInterface $eventBus,
    ) {
    }

    public function __invoke(HowStampWorks $message)
    {
        $this->eventBus->dispatch(new TestUserWasCreated(), [
            //new StopPropagationStamp(),
            new DispatchAfterCurrentBusStamp(),
        ]);

        $user = $this->userRepo->findOneBy(['email' => 's']);
        //$user?->getPassport()?->setName('11');

        $response = 'USER WAS CREATED';
        //throw new UnrecoverableMessageHandlingException();
        \dump($response);
        return $response;
    }
}
