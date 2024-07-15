<?php

namespace App\Validation\Validator;

use App\Validation\Constraint\IsRegex;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class IsRegexValidator extends ConstraintValidator {
	
	public function validate(mixed $value, Constraint $constraint) {
		if (!$constraint instanceof IsRegex) {
			throw new UnexpectedTypeException($constraint, IsRegex::class);
		}
		
		if (null == $value) {
			return;
		}
		
		if (!\is_string($value)) {
			throw new UnexpectedTypeException($value, \get_debug_type(''));
		}
		
		if ('php' === $constraint->language) {
			$this->validatePhpRegex($value, $constraint);
			return;
		}
		
		$m = \sprintf('Unexpected language: "%s" of regular expression', $constraint->language);
		throw new \Exception($m);
	}
	
	private function validatePhpRegex(string $regex, Constraint $constraint): void {
		try {
			\preg_match($regex, '');
		} catch (\Exception $e) {
			$this->context->buildViolation($constraint->message)
				->setParameter('{{ language }}', $constraint->language)
				->setParameter('{{ regex }}', $regex)
				->addViolation()
			;
		}
	}
}