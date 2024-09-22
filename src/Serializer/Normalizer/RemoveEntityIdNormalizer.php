<?php

namespace App\Serializer\Normalizer;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\Service\Attribute\Required;
use App\Contract\Entity\EntityInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class RemoveEntityIdNormalizer implements NormalizerInterface
{
	private NormalizerInterface $normalizer;
	
	#[Required]
	public function _setRequired(
		#[Autowire(service: 'serializer.normalizer.object')]
		NormalizerInterface $normalizer,
	): void {
		$this->normalizer = $normalizer;
	}
	
    public function normalize(
        mixed $object,
        ?string $format = null,
        array $context = []
    ): array|string|int|float|bool|\ArrayObject|null {
        $array = $this->normalizer->normalize($object, $format, $context);
        
		unset($array['id']);
		
		return $array;
    }

    public function supportsNormalization(
        mixed $data,
        ?string $format = null,
        array $context = []
    ): bool {
        return $data instanceof EntityInterface;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            'object' => false,
            //EntityInterface::class => true,
        ];
    }
}
