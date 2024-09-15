<?php

namespace App\Controller;

use function Symfony\component\string\u;

use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Carbon\Carbon;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/onesignal', name: 'app_onesignal_')]
class OnesignalController extends AbstractController
{
	#[Route('/push/subscribe', name: 'push_subscribe')]
    public function pushSubscribe(
		Request $request,
		HttpClientInterface $client,
		#[Autowire('%env(APP_ONESIGNAL_ID)%')]
		$onesignalAppId,
		PropertyAccessorInterface $pa,
	) {
        $dumpResult = [];
		
		$userAgent = $request->headers->all('User-Agent');
		$userAgent = \array_shift($userAgent);
		
		$clientIp = $request->getClientIp();
		$identifier = \md5($userAgent.$clientIp);
		$locale = $request->getSession()->get('_locale');
		$externalUserId = Uuid::v4();
		$timezone = '0';
		// difficult TODO: Mobile-detect
		//$deviceModel = $pa->getValue(u($userAgent)->match('~(?<device_model>.+)~'), '[device_model?]');
		//$deviceOs = $pa->getValue(u($userAgent)->match('~(?<device_os>.+)~'), '[device_os?]');
		$createdAt = Carbon::now('UTC')->timestamp;
		
		$uri = 'https://onesignal.com/api/v1/players';
		// https://documentation.onesignal.com/reference/add-a-device
		$options = [
			'headers' => [
				'Content-Type' => 'application/json',
				'Accept' => 'application/json',
			],
			'json' => [
				'app_id' => $onesignalAppId,
				'identifier' => $identifier,
				'language' => $locale,
				'external_user_id' => $externalUserId,
				'timezone' => $timezone,
				//'device_model' => $deviceModel,
				//'device_os' => $deviceOs,
				'created_at' => $createdAt,
			],
		];
		
		if (\preg_match('~ANDROID~i', $userAgent)) {
			$options['json']['device_type'] = '1';

			$response = $client->request('POST', $uri, $options);

			$dumpResult['set']['status_code'] = $response->getStatusCode();
			$dumpResult['set']['identifier'] = $identifier;
			$dumpResult['set']['external_user_id'] = $externalUserId;
			$dumpResult['set']['language'] = $locale;
			$dumpResult['set']['timezone'] = $timezone;
			//$dumpResult['set']['device_model'] = $deviceModel;
			//$dumpResult['set']['device_os'] = $deviceOs;
			$dumpResult['created_at'] = $createdAt;
			
			$dumpResult['response_body'] = $response->toArray();
		}
		
		return $this->json($dumpResult);
    }
}
