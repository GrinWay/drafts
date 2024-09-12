<?php

namespace App\EventListener\Doctrine\User;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use App\Entity\User;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\PrePersistEventArgs;
use App\Messenger\Notifier\SendEmail;
use Symfony\Component\Messenger\MessageBusInterface;
use App\Service\Doctrine\TaskEntityUtils;
use Symfony\Component\String\Slugger\SluggerInterface;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Totp\TotpAuthenticatorInterface;

#[AsEntityListener(
    entity:     User::class,
    event:      Events::prePersist,
)]
class TotpSecretPrePersistEventLisener
{
    public function __construct(
        private readonly ?TotpAuthenticatorInterface $totpAuthenticator = null,
    ) {
    }

    public function __invoke(
        User $obj,
        PrePersistEventArgs $args,
    ): void {
        if (null === $this->totpAuthenticator) {
            return;
        }
        $totpSecret = $this->totpAuthenticator->generateSecret();
        $obj->setTotpSecret($totpSecret);
    }
}
