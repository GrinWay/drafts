<?php

namespace App\EventListener\Workflow;

use function Symfony\component\string\u;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\SwitchUserToken;
use Symfony\Component\Translation\LocaleSwitcher;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use App\Service\StringService;
use App\Security\Badge\ModifyUserPropBadge;
use Symfony\Component\Security\Http\EventListener\CheckCredentialsListener;
use Symfony\Component\Workflow\Event\GuardEvent;

#[AsEventListener(
	event: 'workflow.user_order.guard.sent_to_delivery',
)]
class UserOrderGuardEventListener
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    ) {
    }

    public function __invoke(GuardEvent $event): void
    {
		$entity = $event->getSubject();
		
		\dump(
			\sprintf($event->getMetadata('block_explanation', null), 'Not worth'),
			$event->getMarking()->getContext(),
		);
		
		if (null === $entity) {
			$event->setBlocked(true, 'Entity is null, you can\'t change a place');			
		}
		
		$event->getMarking()->setContext([
			'message_from_guard_event_listener' => $event->getTransition()->getName(),
		]);
    }
}
