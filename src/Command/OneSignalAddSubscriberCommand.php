<?php

namespace App\Command;

use Detection\MobileDetect;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\Service\Attribute\Required;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Console\Cursor;
use Symfony\Component\Process\Process;
use Symfony\Component\Filesystem\Filesystem;
use App\Service;
use GrinWay\Command\Contracts\IO as IODumper;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;

/**
 * https://documentation.onesignal.com/reference/add-a-device
 * In order to force mobileDetect work properly you have to pass env server vars wher HTTP_USER_AGENT places 
 * 
 * Usage:
 *     Process::fromShellCommandline('php "../bin/console" app:onesignal:subscribe', env: $request->server->all())
 */
#[AsCommand(
    name: 'app:onesignal:subscribe',
)]
class OneSignalAddSubscriberCommand extends AbstractCommand
{
    public const HELP = '';
    public const DESCRIPTION = '';
    
	//###> REQUIRED ###
	private HttpClientInterface $oneSignalClient;
	private string $oneSignalAppId;
	private MobileDetect $mobileDetect;

	#[Required]
	public function _setRequired(
		HttpClientInterface $oneSignalClient,
		#[Autowire('%env(APP_ONESIGNAL_ID)%')] string $oneSignalAppId,
		$mobileDetect,
	): void {
		$this->oneSignalClient = $oneSignalClient;
		$this->oneSignalAppId = $oneSignalAppId;
		$this->mobileDetect = $mobileDetect;
	}
	//###< REQUIRED ###
	
    protected function command(
        InputInterface $input,
        OutputInterface $output,
    ): int {
		$deviceType = null;
		
		if ($this->mobileDetect->isMobile() || $this->mobileDetect->isTablet()) {
			\dump('Mobile device was detected');
			$deviceType = 1;
			$deviceModel = 'Zenfone 10';
			$deviceToken = 'e2snm8uYiP7F7pkPoAEchP:APA91bH-y7Gn7OdKjxVGGN-ZyNeIhvCCV8AOqHhxN1_rSTnqXSfoX2twyoIZbm7PwIJkxCVXhFFykG_f4-D1z5EBiKskSJhmjov6R7SpvYrhaZdWuIP5whQ';
			$language = 'ru';
		} else {
			\dump('That is not mobile');

			// TODO: REMOVE
			$deviceType = 6;
			$deviceModel = 'GIGABYTE X17 AERO';
			$deviceToken = 'cRRK0hB8GFBxLHEJrm0QKS:APA91bF1hpFEPAqHjscDiFbTFWJHpXfmLDIKGynqtx4CpqeTBU_JtDigVvoHO2sfvKMb5eIxbFu6Lb7NPJpajcKmiUY011VKASeltLmlKOkCxQc-eNZxrKk';
			$language = 'en';
		}
		

		if (null === $deviceType) {
			\dump('Device type was not detected "do nothing"');
			return Command::SUCCESS;
		}
		
		$method = 'POST';
		$uri = 'api/v1/players';
		$headers = [];
		$json = [
			'app_id' => $this->oneSignalAppId,
			
			// todo: define and save to user
			'identifier' => $deviceToken,
			
			// todo: detect these things
			'device_type' => $deviceType,
			'device_model' => $deviceModel,
			'language' => $language,
			'timezone' => '+10800',
			'timezone_id' => 'Europe/Moscow',
			/*
			'tags' => [
				'test' => '1',
			],
			*/
		];
		$payload = [
			'headers' => $headers,
			'json' => $json,
		];
		
		$response = $this->oneSignalClient->request($method, $uri, $payload);
		
		$message = \sprintf(
			'Request to [%s]"%s" was finished with status code: "%s"',
			$method,
			$uri,
			$response->getStatusCode(),
		);
		//$this->ioDump($message);
		\dump($message);
		
        return Command::SUCCESS;
    }
}
