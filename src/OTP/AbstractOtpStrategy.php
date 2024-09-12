<?php

namespace App\OTP;

use App\Contract\OTP\OTPStrategyInterface;
use OTPHP\OTP;
use App\Carbon\ClockImmutable;

//TODO: AbstractOtpStrategy
abstract class AbstractOtpStrategy implements OTPStrategyInterface
{
    protected OTP $otp;
    protected mixed $secret;

    public function __construct(
        protected readonly mixed $input,
    ) {
    }

    public function initOtp(ClockImmutable $clock, mixed $secret): static
    {
        if (null === $secret) {
            throw new \LogicException('Configure your secret');
        }

        $otpClass = $this->getOtpClass();

        $secret = \base64_encode((string) $secret);
        $this->otp = $otpClass::createFromSecret($secret, $clock);

        $this->otp->setParameter($this->getInputKey(), $this->getInputValue());

        return $this;
    }

    public function getOtp(): OTP
    {
        return $this->otp;
    }

    public function getInputValue(): mixed
    {
        return $this->input;
    }
}
