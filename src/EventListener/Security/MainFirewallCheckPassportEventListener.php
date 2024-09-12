<?php

namespace App\EventListener\Security;

use Symfony\Component\Security\Http\Event\CheckPassportEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

#[AsEventListener(
    dispatcher: 'security.event_dispatcher.main',
)]
class MainFirewallCheckPassportEventListener
{
    public function __construct()
    {
    }

    public function __invoke(CheckPassportEvent $e): void
    {
        return;
        throw new AuthenticationException();
        \dump('SECURITY: CheckPassportEvent of MAIN firewall', $e->getAuthenticator(), $e->getPassport());
    }
}
