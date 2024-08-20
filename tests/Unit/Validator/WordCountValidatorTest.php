<?php

namespace App\Tests\Unit\Validator;

use App\Validation\Constraint\WordCount;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;
use App\Validation\Validator\WordCountValidator;
use PHPUnit\Framework\Attributes as PHPUnitAttr;
use App\Test\DataProvider\Validator\ConstraintsProvider;

#[PHPUnitAttr\CoversClass(ConstraintsProvider::class)]
class WordCountValidatorTest extends ConstraintValidatorTestCase
{
	protected function createValidator(): ConstraintValidatorInterface {
		return new WordCountValidator;
	}
	
	public function testValidNull() {
		$constraint = new WordCount(min: 1, max: 2);
		$this->validator->validate(null, $constraint);
        $this->assertNoViolation();
	}
	
	public function testValidBoolFalse() {
		$constraint = new WordCount(min: 1, max: 2);
		$this->validator->validate(false, $constraint);
        $this->assertNoViolation();
	}
	
	public function testValidBoolTrue() {
		$constraint = new WordCount(min: 1, max: 2);
		$this->validator->validate(true, $constraint);
        $this->assertNoViolation();
	}
	
	public function testValid1WordWhenMin1Max2() {
		$constraint = new WordCount(min: 1, max: 2);
		$this->validator->validate(' word1 ', $constraint);
        $this->assertNoViolation();
	}
	
	public function testValid1NumberWhenMin1Max2() {
		$constraint = new WordCount(min: 1, max: 2);
		$this->validator->validate(1112, $constraint);
        $this->assertNoViolation();
	}
	
	public function testValid2WordsWhenMin1Max2() {
		$constraint = new WordCount(min: 1, max: 2);
		$this->validator->validate('word1 word2', $constraint);
        $this->assertNoViolation();
	}
	
	public function testValid2WordsWithSpacesWhenMin1Max2() {
		$constraint = new WordCount(min: 1, max: 2);
		$this->validator->validate(' 	   word1  	 	 word2 		 	', $constraint);
        $this->assertNoViolation();
	}
	
	public function testViolationOn3WordsWhenMin1Max2() {
		$constraint = new WordCount(min: 1, max: 2);
		
		$this->validator->validate('word1 word2 word3', $constraint);
        
		$this
			->buildViolation($constraint->maxMessage)
			->setParameter('{passed_len}', 3)
			->setParameter('{must_len}', 2)
			->assertRaised()
		;
	}
	
	public function testViolationOnSpacesStringWhenMin1Max2() {
		$constraint = new WordCount(min: 1, max: 2);
		
		$this->validator->validate('		   	 	 	 	 	 ', $constraint);
        
		$this
			->buildViolation($constraint->minMessage)
			->setParameter('{passed_len}', 0)
			->setParameter('{must_len}', 1)
			->assertRaised()
		;
	}
	
	public function testViolationOnEmptyStringWhenMin1Max2() {
		$constraint = new WordCount(min: 1, max: 2);
		
		$this->validator->validate('', $constraint);
        
		$this
			->buildViolation($constraint->minMessage)
			->setParameter('{passed_len}', 0)
			->setParameter('{must_len}', 1)
			->assertRaised()
		;
	}
	
	public function testViolationOnNumberWhenExactlyMin2Max2() {
		$constraint = new WordCount(min: 2, max: 2);
		
		$this->validator->validate(0x01, $constraint);
        
		$this
			->buildViolation($constraint->exactlyMessage)
			->setParameter('{passed_len}', 1)
			->setParameter('{must_len}', 2)
			->assertRaised()
		;
	}
	
	public function testViolationOn1WordWhenExactlyNumberMin22Max22() {
		$constraint = new WordCount(min: 22, max: 22);
		
		$this->validator->validate('word1', $constraint);
        
		$this
			->buildViolation($constraint->exactlyMessage)
			->setParameter('{passed_len}', 1)
			->setParameter('{must_len}', 22)
			->assertRaised()
		;
	}
}