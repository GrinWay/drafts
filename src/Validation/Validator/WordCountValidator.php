<?php

namespace App\Validation\Validator;

use function Symfony\Component\String\u;
use App\Validation\Constraint\WordCount;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class WordCountValidator extends ConstraintValidator {
	
	public function validate(mixed $value, Constraint $constraint) {
		if (!$constraint instanceof WordCount) {
			throw new UnexpectedTypeException($constraint, WordCount::class);
		}
		
		if (null == $value) {
			return;
		}
		
		$words = \explode(' ', u($value)->collapseWhitespace().'');
		$wordsCount = \count($words);
		
		if ($constraint->min === $constraint->max && $constraint->min > $wordsCount) {
			$this->context->buildViolation($constraint->exactlyMessage)
				->setParameter('{passed_len}', $wordsCount)
				->setParameter('{must_len}', $constraint->min)
				->addViolation()
			;
		}
		
		if ($constraint->min > $wordsCount) {
			$this->context->buildViolation($constraint->minMessage)
				->setParameter('{passed_len}', $wordsCount)
				->setParameter('{must_len}', $constraint->min)
				->addViolation()
			;
		}
		
		if ($constraint->max < $wordsCount) {
			$this->context->buildViolation($constraint->maxMessage)
				->setParameter('{passed_len}', $wordsCount)
				->setParameter('{must_len}', $constraint->max)
				->addViolation()
			;
		}
	}
}