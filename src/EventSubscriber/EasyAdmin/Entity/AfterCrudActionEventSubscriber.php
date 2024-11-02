<?php

namespace App\EventSubscriber\EasyAdmin\Entity;

use EasyCorp\Bundle\EasyAdminBundle\Event\AfterCrudActionEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AfterCrudActionEventSubscriber implements EventSubscriberInterface
{
	public static function getSubscribedEvents()
    {
        return [
            //AfterCrudActionEvent::class => ['onEvent'],
        ];
    }
	
	public function onEvent(AfterCrudActionEvent $event): void {
		\dump(__FUNCTION__.' '.AfterCrudActionEvent::class);
	}
}