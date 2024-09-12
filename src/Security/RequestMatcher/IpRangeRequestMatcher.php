<?php

namespace App\Security\RequestMatcher;

use function Symfony\component\string\u;

use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;
use Symfony\Component\HttpFoundation\Request;

// TODO: IpRangeRequestMatcher
class IpRangeRequestMatcher implements RequestMatcherInterface
{
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

    public function matches(Request $request): bool
    {
        $ip = $request->getClientIp();

        $match = u($ip)->match('~(?<idx0>[0-9]+)[.](?<idx1>[0-9]+)[.](?<idx2>[0-9]+)[.](?<idx3>[0-9]+)~');

        $pa = PropertyAccess::createPropertyAccessor();

        $idx0 = $pa->getValue($match, '[idx0?]');
        $idx1 = $pa->getValue($match, '[idx1?]');
        $idx2 = $pa->getValue($match, '[idx2?]');
        $idx3 = $pa->getValue($match, '[idx3?]');

        $anyIsNull = null === $idx0 || null === $idx1 || null === $idx2 || null === $idx3;

        if ($anyIsNull) {
            return false;
        }

        $isIpValid = static function ($min, $max) {
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
