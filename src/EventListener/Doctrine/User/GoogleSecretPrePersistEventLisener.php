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
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Google\GoogleAuthenticator;

#[AsEntityListener(
    entity:     User::class,
    event:      Events::prePersist,
)]
class GoogleSecretPrePersistEventLisener
{
    public function __construct(
        private readonly ?GoogleAuthenticator $googleAuthenticator = null,
    ) {
    }

    public function __invoke(
        User $obj,
        PrePersistEventArgs $args,
    ): void {
        if (null === $this->googleAuthenticator) {
            return;
        }
        $secret = $this->googleAuthenticator->generateSecret();
        $obj->setGoogleSecret($secret);
    }
}
