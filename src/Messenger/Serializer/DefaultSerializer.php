<?php

namespace App\Messenger\Serializer;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Symfony\Component\Serializer\SerializerInterface as SerializerI;

class DefaultSerializer implements SerializerInterface
{
    public function __construct(
        private readonly SerializerI $serializer,
    ) {
    }

    public function decode(array $encodedEnvelope): Envelope
    {

        $message = $this->serializer->deserialize(
            $encodedEnvelope['body'],
            '???',
            'json',
        );
        $envelope = new Envelope($message);

        foreach ($encodedEnvelope['headers'] as $stamp) {
            $stamps = $this->serializer->denormalize(
                $stamp,
                'json',
            );
            $envelope = $envelope->with($stamp);
        }

        return $envelope;
    }

    public function encode(Envelope $envelope): array
    {
        $body = $this->serializer->serialize(
            $envelope->getMessage(),
            'json',
        );

        $headers = $this->serializer->normalize(
            $envelope->all(),
        );

        $envelope = [
            'body' => $body,
            'headers' => $headers,
        ];

        return $envelope;
    }
}
