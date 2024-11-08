<?php

namespace App\EventListener\Doctrine\UserOrder;

use Symfony\Component\Workflow\WorkflowInterface;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use App\Entity\UserOrder;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\PrePersistEventArgs;

#[AsEntityListener(
    entity:     UserOrder::class,
    event:      Events::prePersist,
)]
class InitUserOrderStatus
{
    public function __construct(
        private readonly WorkflowInterface $userOrderStateMachine,
    ) {
    }

    public function __invoke(
        UserOrder $entity,
        PrePersistEventArgs $args,
    ): void {
		$this->userOrderStateMachine->getMarking($entity);
	}
}