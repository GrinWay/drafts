<?php

namespace App\EventListener\Security;

use function Symfony\component\string\u;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Http\Event\CheckPassportEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\SwitchUserToken;
use Symfony\Component\Translation\LocaleSwitcher;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use App\Service\StringService;

#[AsEventListener]
class CheckPassportModifyUserPropBadgeEventListener {
	
	public const FIND_BADGE_CLASS = ModifyUserPropBadge::class;
	
	public function __construct() {}
	
	public function __invoke(CheckPassportEvent $e): void {
		
		$passport = $e->getPassport();
		
		$badges = $passport->getBadges();
		
		//TODO: current
		\dd($badges);
		
	}
}