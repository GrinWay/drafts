<?php

namespace App\OTP;

use OTPHP\HOTP;

//TODO: HOTPStrategy
class HOTPStrategy extends AbstractOtpStrategy
{
	public function getOtpClass(): string {
		return HOTP::class;
	}
	
	public function getInputKey(): mixed {
		return 'counter';
	}
	
	public function expiresIn(): null {
		return null;
	}
	
	public function getChangingFactor(): mixed {
		return $this->input;
	}
}