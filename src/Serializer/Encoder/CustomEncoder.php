<?php

namespace App\Serializer\Encoder;

use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Symfony\Component\Serializer\Encoder\DecoderInterface;

class CustomEncoder implements EncoderInterface, DecoderInterface {
	public const FORMAT = 'custom';
	
	public function encode(mixed $data, string $format, array $context = []): string
    {
        return 'serialized object'.\get_class($data);
    }

    public function supportsEncoding(string $format): bool
    {
        return self::FORMAT === $format;
    }

    public function decode(string $data, string $format, array $context = []): array
    {
        return [
			'$data' => $data,
		];
    }

    public function supportsDecoding(string $format): bool
    {
        return self::FORMAT === $format;
    }
}