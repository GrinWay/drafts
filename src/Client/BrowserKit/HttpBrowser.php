<?php

namespace App\Client\BrowserKit;

use Symfony\Component\BrowserKit\Response;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\Service\Attribute\Required;
use Symfony\Component\BrowserKit\History;
use Symfony\Component\BrowserKit\CookieJar;
use Symfony\Component\BrowserKit\HttpBrowser as SymfonyHttpBrowser;

class HttpBrowser extends SymfonyHttpBrowser
{
	protected function getHeaders(Request $request): array
	{
		$headers = parent::getHeaders($request);
		return $headers;
	}
}