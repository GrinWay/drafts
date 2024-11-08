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
use Symfony\Component\Workflow\Event\EnterEvent;

#[AsEventListener(
	event: 'workflow.user_order.enter',
)]
class UserOrderEnterEventListener
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    ) {
    }

    public function __invoke(EnterEvent $event): void
    {
		$context = $event->getContext();
		
		$event->getMarking()->setContext([
			'message_from_successful_enter_event_listener' => $event->getTransition()->getName(),
		]);
    }
}
