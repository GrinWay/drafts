<?php

namespace App\OTP;

use OTPHP\TOTP;

//TODO: TOTPStrategy
class TOTPStrategy extends AbstractOtpStrategy
{
    public function getOtpClass(): string
    {
        return TOTP::class;
    }

    public function getInputKey(): mixed
    {
        return 'period';
    }

    public function expiresIn(): int
    {
        return $this->otp->expiresIn();
    }

    public function getChangingFactor(): mixed
    {
        return \time();
    }
}
