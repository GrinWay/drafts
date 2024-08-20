<?php

namespace App\EventListener\Doctrine\Task;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use App\Entity\User;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use App\Messenger\Notifier\SendEmail;
use Symfony\Component\Messenger\MessageBusInterface;
use App\Service\Doctrine\TaskEntityUtils;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Entity\Task;

#[AsEntityListener(
    entity:     Task::class,
    event:      Events::preUpdate,
)]
class TaskPreUpdateEventLisener
{
    public function __construct(
        private readonly SluggerInterface $slugger,
    ) {
    }

    public function __invoke(
        Task $obj,
        PreUpdateEventArgs $args,
    ): void {
        $obj->setSlug(TaskEntityUtils::getSlug($this->slugger, $obj));
    }
}
