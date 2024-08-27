<?php

namespace App\Service;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use OTPHP\OTP;
use App\Carbon\ClockImmutable;
use OTPHP\TOTP;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use OTPHP\HOTP;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Request;

//TODO: OTPUtil
//TODO: current
class OTPUtil
{
	private OTP $otp;
	private ClockImmutable $clock;
	
	public function __construct(
		protected readonly Request $request,
		protected string $otpClass,
		protected string $clientSecret,
		protected array $parameters,
	) {
		$this->otpClass     = $otpClass;
		$this->clientSecret = $clientSecret;

		$this->clock        = new ClockImmutable('UTC');
		
		$parametersResolver = new OptionsResolver();
		$this->resolveParameters($parametersResolver, $parameters);
		$this->parameters = $parametersResolver->resolve($parameters);
		
		$this->doInitByState();
	}
	
	//###> API ###
	
	public function getOtp(): OTP {
		return $this->otp;
	}
	
	public function getExpiresInCarbonForUser(string $clientTz): ?Carbon {
		if (null === $this->parameters['period']) {
			return null;
		}
		
		$session = $this->request->getSession();
		$defaultLocale = $this->request->getLocale();
		
		return $this->clock->add($this->otp->expiresIn(), 'second')
			->tz($clientTz)
			->locale($session?->get('_locale', $defaultLocale) ?? $defaultLocale)
		;
	}
	
	//###< API ###
	
	protected function resolveParameters(OptionsResolver $parametersResolver, array $parameters): void {
		$otpClass = $this->otpClass;
		$messageHaveToPass = static fn($parameter) => \sprintf(
			'Using optClass: "%s", you have to pass "%s" in parameters',
			$optClass,
			$parameter,
		);
		$messageDoNotHaveToPass = static fn($parameter) => \sprintf(
			'Using optClass: "%s", you do NOT have to pass "%s" in parameters',
			$optClass,
			$parameter,
		);
		$typeChecker = static fn($_otpClass): bool => $_otpClass === $otpClass || \is_subclass_of($otpClass, $_otpClass);
		$parametersResolver
			->setDefined([
				'label',
			])
			->setDefaults([
				'algorithm' => 'sha1',
				'digits'    => 6,
				'period'    => null,
				'counter'   => null,
			])
			->setAllowedTypes('period', ['null', 'int'])
			->setAllowedTypes('counter', ['null', 'int'])
			->setAllowedTypes('label', 'string')
			->setAllowedTypes('algorithm', 'string')
			->setAllowedTypes('digits', 'int')
			->setNormalizer('period', static function (Options $options, $value) use ($otpClass, $messageDoNotHaveToPass, $typeChecker) {
				if ($typeChecker(TOTP::class)) {
					if (null !== $value) {
						throw new \InvalidArgumentException($messageDoNotHaveToPass('counter'));						
					}
				}
				return $value;
			})
			->setNormalizer($parameterName = 'period', static function (Options $options, $value) use ($otpClass, $messageHaveToPass, $parameterName, $typeChecker) {
				if ($typeChecker(TOTP::class)) {
					if (null === $value) {
						throw new \InvalidArgumentException($messageHaveToPass($parameterName));						
					}
				}
				return $value;
			})
			->setNormalizer('counter', static function (Options $options, $value) use ($otpClass, $messageDoNotHaveToPass, $typeChecker) {
				if ($typeChecker(HOTP::class)) {
					if (null !== $value) {
						throw new \InvalidArgumentException($messageDoNotHaveToPass('period'));						
					}
				}
				return $value;
			})
			->setNormalizer($parameterName = 'counter', static function (Options $options, $value) use ($otpClass, $messageHaveToPass, $parameterName, $typeChecker) {
				if ($typeChecker(HOTP::class)) {
					if (null === $value) {
						throw new \InvalidArgumentException($messageHaveToPass($parameterName));						
					}
				}
				return $value;
			})
		;
	}
	
	private function doInitByState(): void {
		$baseSecret = \base64_encode($this->clientSecret);
		$this->otp = $this->otpClass::createFromSecret($baseSecret, $this->clock);
		foreach($this->parameters as $name => $parameter) {
			$this->otp->setParameter($name, $parameter);
		}
	}
}
