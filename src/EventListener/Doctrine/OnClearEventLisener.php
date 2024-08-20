<?php

namespace App\EventListener\Doctrine;

use function Symfony\Component\String\u;

use Symfony\Component\Messenger\Stamp\TransportNamesStamp;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\Context\Encoder\JsonEncoderContextBuilder;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use App\Entity\User;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\OnClearEventArgs;
use App\Messenger\Notifier\SendEmail;
use Symfony\Component\Messenger\MessageBusInterface;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Messenger\Envelope;

#[AsDoctrineListener(
    event: Events::onClear,
)]
class OnClearEventLisener
{
    public function __construct(
        private readonly MessageBusInterface $bus,
        private readonly SerializerInterface $serializer,
        private readonly string $adminEmail,
    ) {
    }

    public function __invoke(
        OnClearEventArgs $args,
    ): void {
        /*
        $message = new SendEmail(
            $this->adminEmail,
            'Event happened',
            Events::onClear,
        );
        $stamps = [
            new TransportNamesStamp([
                'async',
                'sync',
            ]),
        ];
        $this->bus->dispatch(new Envelope($message, $stamps));
        */
    }
}
