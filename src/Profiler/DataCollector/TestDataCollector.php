<?php

namespace App\Profiler\DataCollector;

use Symfony\Bundle\FrameworkBundle\DataCollector\AbstractDataCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

//#[AutoconfigureTag('data_collector', [ 'id' => 'app.test', 'priority' => 0 ])]
class TestDataCollector //extends AbstractDataCollector
{
	public function collect(Request $request, Response $response, ?\Throwable $exception = null): void
    {
        $this->data = [
            'test' => 'absolutely any service data',
        ];
    }
	
	public function getName(): string
    {
        return 'app.test';
    }
	
	/**
     * API
     */
	public function getTestData() {
		return $this->data['test'];
	}
	
	public static function getTemplate(): ?string
    {
        return 'profiler/test/index.html.twig';
    }
}