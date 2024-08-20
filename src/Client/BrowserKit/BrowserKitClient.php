<?php

namespace App\Client\BrowserKit;

use Symfony\Component\BrowserKit\AbstractBrowser;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\Service\Attribute\Required;
use Symfony\Component\BrowserKit\History;
use Symfony\Component\BrowserKit\CookieJar;

class BrowserKitClient extends AbstractBrowser
{
	private readonly string $requiredScheme;
	
    public function __construct(
		string $host,
		string $requiredScheme,
		array $server = [],
		?History $history = null,
		?CookieJar $cookieJar = null,
	) {
		$this->requiredScheme = \strtolower($requiredScheme);
		
		$server = \array_merge([
			'HTTPS' => 'https' === $this->requiredScheme,
			'HTTP_HOST' => $host,
		], $server);
		
        parent::__construct(
			server: $server,
			history: $history,
			cookieJar: $cookieJar,
		);
		
    }
	
	protected function doRequest($request): Response
    {
		$browser = new HttpBrowser(HttpClient::create([
			//'verify_peer' => false, // lag
			'verify_host' => false,
		]));
        $browser->request($request->getMethod(), $request->getUri());
		return $browser->getResponse();
    }

}