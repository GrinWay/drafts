<?php

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class ObjHasDateTimeFormHasTextDataTrasformer implements DataTransformerInterface {
    public function __construct(
		private $enUtcCarbon,
	) {}
	
    public function transform(mixed $value): string {
		// for an empty form
		if (null === $value) return '';
		return $this->enUtcCarbon->make($value)->format(\DateTime::COOKIE);
	}
	
    public function reverseTransform(mixed $value): \DateTimeInterface {
		try {
			$dateTime = $this->enUtcCarbon->make($value);
		} catch (\Exception $e) {
			$message = \sprintf(
				'Impossible to create "%s" object from string: "%s"',
				\DateTimeInterface::class,
				$value,
			);
			throw new TransformationFailedException($message, invalidMessage: $message);
		}
		return $dateTime;
	}
}