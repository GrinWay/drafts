<?php

namespace App\EventListener\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use App\Entity\User;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use App\Messenger\Notifier\SendEmail;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsEntityListener(
    entity:     User::class,
    event:      Events::postUpdate,
)]
class UserPostUpdateEventLisener
{
    public function __construct(
        private readonly MessageBusInterface $bus,
    ) {
    }

    public function __invoke(
        User $user,
        PostUpdateEventArgs $args,
    ): void {
        /*
        $this->bus->dispatch(
            new SendEmail($user->getEmail(), Events::postUpdate, \get_class($user) . ' ' . $user->getId())
        );
        */
    }
}
