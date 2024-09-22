<?php

namespace App\Serializer\Normalizer;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\Service\Attribute\Required;
use App\Serializer\Normalizer\ContextBuilder\EmptyContextBuilder;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class EmptyDenormalizer implements DenormalizerInterface
{
	private DenormalizerInterface $denormalizer;
	
	#[Required]
	public function _setRequired(
		#[Autowire(service: 'serializer')]
		DenormalizerInterface $denormalizer,
	): void {
		$this->denormalizer = $denormalizer;
	}
	
	public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
	{
		if (empty($data)) {
			$data = null;
		}
		
		if (\is_array($data)) {
			$returnArray = [];
			foreach($data as $k => $v) {
				$returnArray[$k] = empty($v) ? null : $v;
			}
			$data = $returnArray;
		}
		
		unset($context[EmptyContextBuilder::EMPTY_CASTING_KEY]);
		
		$object = $this->denormalizer->denormalize($data, $type, $format, $context);
		
		return $object;
	}
	
	public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
	{
		return isset($context[EmptyContextBuilder::EMPTY_CASTING_KEY]);
	}
	
	public function getSupportedTypes(?string $format): array
	{
		return [
			'*' => false,
		];
	}
}
