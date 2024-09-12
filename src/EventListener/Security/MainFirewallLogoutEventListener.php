<?php

namespace App\EventListener\Security;

use Symfony\Component\Security\Http\Event\LogoutEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(
    dispatcher: 'security.event_dispatcher.main',
)]
class MainFirewallLogoutEventListener
{
    public function __construct()
    {
    }

    public function __invoke(LogoutEvent $e): void
    {
        \dump('SECURITY: LogoutEvent of MAIN firewall', \get_debug_type($e->getToken()));
    }
}
