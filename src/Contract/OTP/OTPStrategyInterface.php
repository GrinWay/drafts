<?php

namespace App\Contract\OTP;

use OTPHP\OTP;
use App\Carbon\ClockImmutable;

//TODO: OTPStrategyInterface
interface OTPStrategyInterface
{
	/*
	 * A certain realization of the OTPStrategyInterface knows it
	 */
	public function getOtpClass(): string;
	
	/*
	 * A certain realization of the OTPStrategyInterface knows it
	 * 
	 * TOTP (period)
	 * HOTP (counter)
	 * and another possible OTP input key by a certain realization...
	 */
	public function getInputKey(): mixed;
	
	/*
	 * A certain realization of the OTPStrategyInterface knows it
	 * 
	 * @return ?int (When null has no expire time)
	 * @return ?int (When int means the rest of seconds the code is still valid)
	 */
	public function expiresIn(): ?int;
	
	/*
	 * A certain realization of the OTPStrategyInterface knows it
	 * 
	 * @return mixed (time() When TOTP)
	 * @return mixed (counter When HOTP)
	 * @return mixed (and other possible OTP changing factors...)
	 */
	public function getChangingFactor(): mixed;
	
	/*
	 * Must set otp property
	 */
	public function initOtp(ClockImmutable $clock, mixed $secret): static;
	
	/*
	 * Gets otp
	 */
	public function getOtp(): OTP;

	/*
	 * @return mixed (period When TOTP)
	 * @return mixed (counter When HOTP)
	 * @return mixed (and other possible OTP inputs...)
	 * 
	 * Supply this with __controller of a certain OTPStrategy
	 */
	public function getInputValue(): mixed;
}