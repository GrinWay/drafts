<?php

namespace App\Messenger\Notifier;

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
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;

#[AsTaggedItem('notifier')]
class NotifierHandlers implements CommandBusHandlerInterface
{
    public function __construct(
        private readonly ProductRepository $productRepository,
        private readonly ProductPassportRepository $productPassportRepository,
        private readonly EntityManagerInterface $em,
        private readonly MessageBusInterface $bus,
        private readonly MessageBusInterface $eventBus,
    ) {
    }

    public function __invoke(
        ToAdminSendEmail $message,
        ?string $baseUrl = null,
        ?string $pathInfo = null,
    ): void {


        //throw new UnrecoverableMessageHandlingException;

        \dump('TO ADMIN EMAIL LOGIC');

        /*
        $this->eventBus->dispatch(new UserWasCreated, [
            new DispatchAfterCurrentBusStamp,
        ]);
        */

        /*
        */

        /*
        $this->bus->dispatch(new SendEmail('son5', 'son5'), [
            new DispatchAfterCurrentBusStamp,
        ]);

        throw new Exception;
        $this->em->flush();
        */
        //throw new RecoverableMessageHandlingException;
        //throw new UnrecoverableMessageHandlingException;
        //throw new StopWorkerException('Worker stop command');
        //$value = $this->osService('make', true, 11);
        //\dump('Sending email to "' . $message->getTo() . '"' . \PHP_EOL . $message);
    }

    /*
    public static function getKeyName(): string {
        return __CLASS__;
    }
    */
}
