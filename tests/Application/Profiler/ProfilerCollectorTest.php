<?php

namespace App\Tests\Application\Profiler;

use Symfony\Component\VarDumper\Cloner\Data;
use App\Tests\Application\AbstractApplicationCase;

class ProfilerCollectorTest extends AbstractApplicationCase {
	public function testProfile(): void {
		$container = self::getContainer();
		$appRequiredScheme = $container->getParameter('router.request_context.scheme');
		$this->ensureKernelShutdown();
		
		$client = static::createClient(
			server: [
				'HTTPS' => \preg_match('~https~i', $appRequiredScheme),
			]
		);
		
		$client->enableProfiler();
		$crawler = $client->request('GET', '/');
		$this->followRedirectsAfterRequest($client);
		
		$profiler = $client->getProfile();
		if ($profiler) {
			//session
			
			/**
			* getStatelessCheck
			* getContentType
			*/
			$requestDataCollector = $profiler->getCollector('request');
			
			$commandDataCollector = $profiler->getCollector('command');
			$timeDataCollector = $profiler->getCollector('time');
			$memoryDataCollector = $profiler->getCollector('memory');
			$validatorDataCollector = $profiler->getCollector('validator');
			$ajaxDataCollector = $profiler->getCollector('ajax');
			$formDataCollector = $profiler->getCollector('form');
			$exceptionDataCollector = $profiler->getCollector('exception');
			$loggerDataCollector = $profiler->getCollector('logger');
			$eventsDataCollector = $profiler->getCollector('events');
			$routerDataCollector = $profiler->getCollector('router');
			$cacheDataCollector = $profiler->getCollector('cache');
			$translationDataCollector = $profiler->getCollector('translation');
			$securityDataCollector = $profiler->getCollector('security');
			$twigDataCollector = $profiler->getCollector('twig');
			$twigComponentDataCollector = $profiler->getCollector('twig_component');
			$httpClientComponentDataCollector = $profiler->getCollector('http_client');
			$dbDataCollector = $profiler->getCollector('db');
			$messengerDataCollector = $profiler->getCollector('messenger');
			$mailerDataCollector = $profiler->getCollector('mailer');
			$workflowDataCollector = $profiler->getCollector('workflow');
			$vichUploaderMappingCollectorDataCollector = $profiler->getCollector('vich_uploader.mapping_collector');
			$configDataCollector = $profiler->getCollector('config');
			
			$getData = static fn($arrayOfData, $m = 'getValue') => \array_map(static fn($data) => $data->$m(), $arrayOfData);
			
			//###> ASSERTIONS
			$this->assertFalse($requestDataCollector->getStatelessCheck());
			
			$this->assertTrue($client->getRequest()->getSession()->has('_locale'));
			$this->assertArrayHasKey('_locale', $getData($requestDataCollector->getSessionAttributes()));
			
			$contentType = $requestDataCollector->getContentType();
			$this->assertStringContainsStringIgnoringCase('text/html', $contentType);
			
			\dd(
				/*
				'profiler:',
				\get_debug_type($profiler),
				'profiler getToken:',
				$profiler->getToken(),
				'profiler getParent:',
				\get_debug_type($profiler->getParent()),
				'profiler getParentToken:',
				$profiler->getParentToken(),
				'profiler getIp:',
				$profiler->getIp(),
				'profiler getMethod:',
				$profiler->getMethod(),
				'profiler getUrl:',
				$profiler->getUrl(),
				'profiler getTime:',
				$profiler->getTime(),
				'profiler getStatusCode:',
				$profiler->getStatusCode(),
				'profiler getVirtualType:',
				$profiler->getVirtualType(),
				'profiler getChildren types:',
				\array_map(static fn($profiler) => \get_debug_type($profiler), $profiler->getChildren()),
				
				'profiler getCollectors:',
				\array_map(static fn($collector) => $collector->getName(), $profiler->getCollectors()),
				'requestDataCollector:',
				\get_debug_type($requestDataCollector),
				'commandDataCollector:',
				\get_debug_type($commandDataCollector),
				'timeDataCollector:',
				\get_debug_type($timeDataCollector),
				$timeDataCollector->getDuration(),
				*/
				'dbDataCollector:',
				\get_debug_type($dbDataCollector),
				$dbDataCollector->getCacheEnabled(),
				/*
				'dbDataCollector getQueryCount:',
				$dbDataCollector->getQueryCount(),
				'memoryDataCollector:',
				\get_debug_type($memoryDataCollector),
				'validatorDataCollector:',
				\get_debug_type($validatorDataCollector),
				'ajaxDataCollector:',
				\get_debug_type($ajaxDataCollector),
				'formDataCollector:',
				\get_debug_type($formDataCollector),
				'exceptionDataCollector:',
				\get_debug_type($exceptionDataCollector),
				'loggerDataCollector:',
				\get_debug_type($loggerDataCollector),
				'eventsDataCollector:',
				\get_debug_type($eventsDataCollector),
				'routerDataCollector:',
				\get_debug_type($routerDataCollector),
				'cacheDataCollector:',
				\get_debug_type($cacheDataCollector),
				'translationDataCollector:',
				\get_debug_type($translationDataCollector),
				'securityDataCollector:',
				\get_debug_type($securityDataCollector),
				'twigDataCollector:',
				\get_debug_type($twigDataCollector),
				'twigComponentDataCollector:',
				\get_debug_type($twigComponentDataCollector),
				'httpClientComponentDataCollector:',
				\get_debug_type($httpClientComponentDataCollector),
				
				
				'messengerDataCollector:',
				\get_debug_type($messengerDataCollector),
				'mailerDataCollector:',
				\get_debug_type($mailerDataCollector),
				'workflowDataCollector:',
				\get_debug_type($workflowDataCollector),
				'vichUploaderMappingCollectorDataCollector:',
				\get_debug_type($vichUploaderMappingCollectorDataCollector),
				'configDataCollector:',
				\get_debug_type($configDataCollector),
				*/
			);			
		}
	}
}
