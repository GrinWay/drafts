<?php

namespace App\Validation\Validator;

use function Symfony\Component\String\u;

use App\Validation\Constraint\WordCount;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use App\Service;

class WordCountValidator extends ConstraintValidator {
	
	public function validate(mixed $value, Constraint $constraint) {
		if (!$constraint instanceof WordCount) {
			throw new UnexpectedTypeException($constraint, WordCount::class);
		}
		
		if (null === $value || \is_bool($value)) {
			return;
		}
		
		if (!\is_scalar($value)) {
			throw new UnexpectedTypeException($constraint, 'scalar');
		}
		
		$countWords = Service\WordUtil::countWords((string) $value);
		
		if ($constraint->min === $constraint->max && ($constraint->min > $countWords || $constraint->max < $countWords)) {
			$this->context->buildViolation($constraint->exactlyMessage)
				->setParameter('{passed_len}', $countWords)
				->setParameter('{must_len}', $constraint->min)
				->addViolation()
			;
			return;
		}
		
		if ($constraint->min > $countWords) {
			$this->context->buildViolation($constraint->minMessage)
				->setParameter('{passed_len}', $countWords)
				->setParameter('{must_len}', $constraint->min)
				->addViolation()
			;
			return;
		}
		
		if ($constraint->max < $countWords) {
			$this->context->buildViolation($constraint->maxMessage)
				->setParameter('{passed_len}', $countWords)
				->setParameter('{must_len}', $constraint->max)
				->addViolation()
			;
			return;
		}
	}
}