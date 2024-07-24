<?php

namespace App\Security\RequestMatcher;

use function Symfony\component\string\u;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;
use Symfony\Component\HttpFoundation\Request;

class IpRangeRequestMatcher implements RequestMatcherInterface {
    
	public function __construct(
		private readonly string|int|null $minIdx0 = null,
		private readonly string|int|null $maxIdx0 = null,
		private readonly string|int|null $minIdx1 = null,
		private readonly string|int|null $maxIdx1 = null,
		private readonly string|int|null $minIdx2 = null,
		private readonly string|int|null $maxIdx2 = null,
		private readonly string|int|null $minIdx3 = null,
		private readonly string|int|null $maxIdx3 = null,
	) {
	}
	
	public function matches(Request $request): bool {
		$ip = $request->getClientIp();
		
		[
			'idx0' => $idx0,
			'idx1' => $idx1,
			'idx2' => $idx2,
			'idx3' => $idx3,
		] = u($ip)->match('~(?<idx0>[0-9]+)[.](?<idx1>[0-9]+)[.](?<idx2>[0-9]+)[.](?<idx3>[0-9]+)~');
		
		$isIpValid = static function($min, $max) {
			$constraints = [];
			if (null !== $min) {
				$constraints[] = new Constraints\GreaterThanOrEqual($min);				
			}
			if (null !== $max) {
				$constraints[] = new Constraints\LessThanOrEqual($max);
			}
			if ([] === $constraints) {
				return static fn() => true;
			}
			
			return Validation::createIsValidCallable(...$constraints);
		};
		$idx0Constraint = $isIpValid($this->minIdx0, $this->maxIdx0);
		$idx1Constraint = $isIpValid($this->minIdx1, $this->maxIdx1);
		$idx2Constraint = $isIpValid($this->minIdx2, $this->maxIdx2);
		$idx3Constraint = $isIpValid($this->minIdx3, $this->maxIdx3);
		
		$isIpsValid = $idx0Constraint($idx0) && $idx1Constraint($idx1) && $idx2Constraint($idx2) && $idx3Constraint($idx3);
		
		return $isIpsValid;
	}
	
}