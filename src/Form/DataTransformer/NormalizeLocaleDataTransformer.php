<?php

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

//TODO: NormalizeLocaleDataTransformer add FormTypeExtension
class NormalizeLocaleDataTransformer implements DataTransformerInterface {
    public function __construct(
	) {
	}
	
    public function transform(mixed $value): ?string {
		return $this->canonicalize($value);
	}
	
    public function reverseTransform(mixed $value): ?string {
		return $this->canonicalize($value);
	}
	
	private function canonicalize(?string $value): ?string {
		if (null === $value) return null;
		
		$value = \trim($value);
		$value = \preg_replace('~\-~', '_', $value);
		$value = \mb_strtolower($value);
		
		$matches = [];
		\preg_match('~^(?<value>(?<first_part>[a-z]{2})_(?<second_part>[a-z]{2}))~', $value, $matches);
		if (isset($matches['value'])) {
			$value = \sprintf(
				'%s_%s',
				$matches['first_part'],
				\mb_strtoupper($matches['second_part']),
			);
		}
		
		return $value;
	}
}