<?php

namespace App\EventListener\Doctrine\User;

use Scheb\TwoFactorBundle\Model\BackupCodeInterface;
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
class BackupCodesPrePersistEventLisener
{
    public function __construct(
        private $faker,
    ) {
    }

    public function __invoke(
        User $obj,
        PrePersistEventArgs $args,
    ): void {
        if (!$obj instanceof BackupCodeInterface) {
            return;
        }
        $obj->addBackUpCode([
            $this->faker->numberBetween(10, 999),
            $this->faker->numberBetween(10, 999),
            $this->faker->numberBetween(10, 999),
            777,
            777,
            777,
            777,
            777,
            777,
            777,
            777,
            777,
        ]);
    }
}
