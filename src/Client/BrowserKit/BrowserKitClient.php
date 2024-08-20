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
    public function __construct(
		?string $host = null,
		?string $requiredScheme = null,
		array $server = [],
		?History $history = null,
		?CookieJar $cookieJar = null,
	) {
		$dopServer = [];
		
		if (null !== $requiredScheme) {
			$requiredScheme = \strtolower($requiredScheme);
			$dopServer = \array_merge([
				'HTTPS' => 'https' === $requiredScheme,
			], $dopServer);			
		}
		
		if (null !== $host) {
			$dopServer = \array_merge([
				'HTTP_HOST' => $host,
			], $dopServer);
		}
		
		$server = \array_merge($dopServer, $server);
		
        parent::__construct(
			server: $server,
			history: $history,
			cookieJar: $cookieJar,
		);
		
    }
	
	protected function doRequest($request): Response
    {
		$browser = new HttpBrowser(HttpClient::create(["verify_peer"=>false,"verify_host"=>false]));
        $browser->request($request->getMethod(), $request->getUri());
		return $browser->getResponse();
    }

}