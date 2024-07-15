<?php

namespace App\Form\DataTransformer;

use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\DataTransformerInterface;
use Doctrine\ORM\EntityManagerInterface;

class ForeignKeyAsTextIdDataTransformer implements DataTransformerInterface {
    private string $entityClass;
	
	public function __construct(
		private EntityManagerInterface $em,
	) {
		$this->entityClass = '';
	}
	
    public function transform(mixed $value): string {
		// for an empty form
		if (null === $value) {
			return '';
		}
		if (!\method_exists($value, $method = 'getId')) {
			$message = \sprintf(
				'Can\'t get id cuz method "%s" does not exists.',
				$method,
			);
			throw new TransformationFailedException($message, invalidMessage: $message);
		}
		$this->entityClass = \get_class($value);
		return $value->getId();
	}
	
    public function reverseTransform(mixed $value): ?object {
		if (null === $value) return null;
		
		if (empty($value)) {
			$message = \sprintf(
				'Won\'t be able to find object by empty foreign key: "%s"',
				$value,
			);
			throw new TransformationFailedException($message, invalidMessage: $message);
		}
		if (null === ($obj = $this->em->getRepository($this->entityClass)->find($value))) {
			$message = \sprintf(
				'The object by foreign key: "%s" wasn\'t found',
				$value,
			);
			throw new TransformationFailedException(
				$message,
				invalidMessage: $message,
			);
		}
		
		return $obj;
	}
}