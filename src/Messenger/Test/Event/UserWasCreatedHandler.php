<?php

namespace App\Messenger\Test\Event;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Exception\StopWorkerException;
use App\Service\OSService;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\Messenger\Exception\RecoverableMessageHandlingException;
use App\Repository\ProductRepository;
use App\Repository\ProductPassportRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DispatchAfterCurrentBusStamp;
use Symfony\Component\Messenger\Handler\Acknowledger;
use Symfony\Component\Messenger\Handler\BatchHandlerInterface;
use Symfony\Component\Messenger\Handler\BatchHandlerTrait;
use App\Contract\Messenger\CommandBusHandlerInterface;
use App\Messenger\Test\Event\UserWasCreated;
use Symfony\Component\Messenger\Message\RedispatchMessage;
use App\Contract\Messenger\EventBusHandlerInterface;

class UserWasCreatedHandler implements EventBusHandlerInterface
{
    public function __construct(
        private readonly ProductRepository $productRepository,
        private readonly ProductPassportRepository $productPassportRepository,
        private readonly EntityManagerInterface $em,
        private readonly MessageBusInterface $bus,
        private readonly MessageBusInterface $eventBus,
    ) {
    }

    public function __invoke(UserWasCreated $event): void
    {
        //\dump('USER WAS CREATED');
        //throw new UnrecoverableMessageHandlingException;
        $product = $this->productRepository->find(31);

        if ($product->getPassport() === null) {
            $product->setPassport($this->productPassportRepository->find(12));
        } else {
            $product->setPassport(null);
        }

        $this->em->flush();
    }
}
