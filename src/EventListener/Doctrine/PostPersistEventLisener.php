<?php

namespace App\EventListener\Doctrine;

use function Symfony\Component\String\u;

use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\Context\Encoder\JsonEncoderContextBuilder;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use App\Entity\User;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use App\Messenger\Notifier\SendEmail;
use Symfony\Component\Messenger\MessageBusInterface;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Symfony\Component\Serializer\SerializerInterface;

#[AsDoctrineListener(
    event: Events::postPersist,
)]
class PostPersistEventLisener
{
    public function __construct(
        private readonly MessageBusInterface $bus,
        private readonly SerializerInterface $serializer,
        private readonly string $adminEmail,
    ) {
    }

    public function __invoke(
        PostPersistEventArgs $args,
    ): void {
        $em = $args->getObjectManager();
        $conn = $em->getConnection();
        $dbName = $conn->getDatabase();

        $entity = $args->getObject();
        $entityClass = \get_class($entity);
        $id = \method_exists($entity, 'getId') ? $entity->getId() : '(without getId method)';
        $id = u($id)->ensureStart($entityClass . ' ');

        $context = (new ObjectNormalizerContextBuilder())
            ->withGroups('app.notifier.admin')
            ->toArray()
        ;
        //\dd($context);
        /*
        $this->bus->dispatch(
            new SendEmail(
                $this->adminEmail,
                'NEW: "' . $id . '" in database: "' . $dbName . '"',
                $this->serializer->serialize($entity, 'json', $context),
            )
        );
        */
    }
}
