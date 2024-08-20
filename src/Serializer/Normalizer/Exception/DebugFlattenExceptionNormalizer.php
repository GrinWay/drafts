<?php

namespace App\Serializer\Normalizer\Exception;

use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class DebugFlattenExceptionNormalizer implements NormalizerInterface
{
    public function normalize(
        mixed $object,
        ?string $format = null,
        array $context = []
    ): array|string|int|float|bool|\ArrayObject|null {
        return [
            'file' => $object->getFile(),
            'line' => $object->getLine(),
            'message' => $object->getMessage(),
            'code' => $object->getCode(),
            'status_text' => $object->getStatusText(),
            'status_code' => $object->getStatusCode(),
        ];
    }

    public function supportsNormalization(
        mixed $data,
        ?string $format = null,
        array $context = []
    ): bool {
        return true
            && $context['debug'] === true
            && $data instanceof FlattenException
        ;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            // exception happened when APP_ENV=prod
            FlattenException::class => true,
        ];
    }
}
