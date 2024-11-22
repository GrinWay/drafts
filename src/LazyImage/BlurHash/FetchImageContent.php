<?php

namespace App\LazyImage\BlurHash;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class FetchImageContent
{
	public function __construct(
		private readonly HttpClientInterface $client,
	) {}
	
	public function __invoke(string $filename): string
    {	
        if (\is_file($filename)) {
			$fileContent = \file_get_contents($filename);
			return $fileContent;
		}
		
		if (\str_starts_with($filename, 'http')) {
			$r = $this->client->request('GET', $filename);
			$data = $r->getContent();
			return $data;
		}	
		
		$message = \sprintf('The "%s" refers to unexsistent filepath', $filename);
		throw new \InvalidArgumentException($message);
    }
}