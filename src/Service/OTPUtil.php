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
use App\Trait\SessionAwareTrait;
use App\Contract\OTP\OTPStrategyInterface;

//TODO: OTPUtil
/*
 * Usage:
 *    $otpUtil = new OTPUtil(
 *        request: $request,
 *        otpStrategy: new TOTPStrategy(input: $secondPeriod),
 *        clientSecret: $clientSecret,
 *        clientParameters: [
 *            'label'     => $label,
 *            'digits'    => $digits,    // is not required
 *            'algorithm' => $algorithm, // is not required
 *        ],
 *    );
 *    
 *    $otp = $otpUtil->getOtp();
 *    $clientsExpiresInCarbon = $otpUtil->getExpiresInCarbonForUser(clientTz: '+01:00');
 *    $code = $otpUtil->getCode();
 *    $secret = $otpUtil->getSecret();
 *    
 *    In your sub classes of OTPUtil you can override: OVERRIDE SECTION
 */
class OTPUtil
{
	use SessionAwareTrait;
	
	private ClockImmutable $clock;
	
	public function __construct(
		protected readonly Request $request,
		protected readonly OTPStrategyInterface $otpStrategy,
		protected string $clientSecret,
		protected array $clientParameters,
	) {
		$this->clock        = new ClockImmutable('UTC');
		
		$parametersResolver = new OptionsResolver();
		$this->defaultResolveParameters($parametersResolver, $clientParameters);
		$this->resolveParameters($parametersResolver, $clientParameters);
		$this->clientParameters = $parametersResolver->resolve($clientParameters);
		
		$this->doInitByState();
	}
	
	
	//###> API ###
	
	/*
	 * @return OTP (OPT instance)
	 */
	public function getOtp(): OTP {
		return $this->otpStrategy->getOtp();
	}
	
	/*
	 * @return mixed (OPT secret)
	 */
	public function getSecret(): mixed {
		return $this->otpStrategy->getOtp()->getSecret();
	}
	
	/*
	 * @return string (OPT code)
	 */
	public function getCode(): string {
		return $this->otpStrategy->getOtp()->at($this->otpStrategy->getChangingFactor());
	}
	
	/*
	 * 
	 */
	public function getExpiresInCarbonForUser(string $clientTz): ?CarbonImmutable {
		$expiresIn = $this->otpStrategy->expiresIn();
		if (null === $expiresIn) {
			return null;
		}
		
		$session = $this->getSession($this->request);
		$defaultLocale = $this->request->getLocale();
		
		return $this->clock->add($expiresIn, 'second')
			->tz($clientTz)
			->locale($session->get('_locale', $defaultLocale))
		;
	}
	
	//###< API ###
	
	
	//###> OVERRIDE SECTION ###
	
	/*
	 * Define new options
	 */
	protected function resolveParameters(OptionsResolver $parametersResolver, array $clientParameters): void {}
	
	//###< OVERRIDE SECTION ###
	
	
	protected function defaultResolveParameters(OptionsResolver $parametersResolver, array $clientParameters): void {
		$otpClass = $this->otpStrategy->getOtpClass();
		$messageHaveToPass = static fn($parameter) => \sprintf(
			'Using otpClass: "%s", you have to pass "%s" in clientParameters',
			$otpClass,
			$parameter,
		);
		$messageDoNotHaveToPass = static fn($parameter) => \sprintf(
			'Using otpClass: "%s", you do NOT have to pass "%s" in clientParameters',
			$otpClass,
			$parameter,
		);
		$typeChecker = static fn($_otpClass): bool => $_otpClass === $otpClass || \is_subclass_of($otpClass, $_otpClass);
		
		$parametersResolver
			->setRequired([
				'label',
			])
			->setDefaults([
				'digits'    => 6,
				'algorithm' => 'sha1',
			])
			->setAllowedTypes('label',      'string')
			->setAllowedTypes('digits',     'int')
			->setAllowedTypes('algorithm',  'string')
		;
	}
	
	private function doInitByState(): void {
		$this->otpStrategy->initOtp($this->clock, $this->clientSecret);
		foreach($this->clientParameters as $name => $parameter) {
			$this->otpStrategy->getOtp()->setParameter($name, $parameter);
		}
	}
}
