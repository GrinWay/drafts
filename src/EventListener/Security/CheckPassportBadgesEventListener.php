<?php

namespace App\EventListener\Security;

use function Symfony\component\string\u;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Http\Event\CheckPassportEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\SwitchUserToken;
use Symfony\Component\Translation\LocaleSwitcher;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use App\Service\StringService;
use App\Security\Badge\ModifyUserPropBadge;
use Symfony\Component\Security\Http\EventListener\CheckCredentialsListener;

#[AsEventListener]
class CheckPassportBadgesEventListener
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    ) {
    }

    public function __invoke(CheckPassportEvent $e): void
    {

        $passport = $e->getPassport();

        if ($passport->hasBadge(ModifyUserPropBadge::class)) {
            $badge = $passport->getBadge(ModifyUserPropBadge::class);
            $user = $passport->getUser();
            $badge($user);
            $this->em->flush();
            return;
        }
    }
}
