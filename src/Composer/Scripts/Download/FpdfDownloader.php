<?php

namespace App\Composer\Scripts\Download;

use Symfony\Component\HttpClient\HttpClient;

class FpdfDownloader
{
	public static function download($event): void {
		return;
		$client = HttpClient::create();
		
		$uri = 'http://www.fpdf.org/en/dl.php?v=186&f=zip';
		
		$fpdfFilename = 'fpdf.zip';
		$absDownloadDir = __DIR__.'/../../../../public/downloads';
		$absDownloadPath = \sprintf($absDownloadDir.'/%s', $fpdfFilename);
		$extractToDir = $absDownloadDir.'/../../vendor/fpdf';
		
		//###> Download .zip to downloads
		if (!\is_dir($extractToDir) && !\is_file($absDownloadPath)) {
			$res = \fopen($absDownloadPath, 'w');
			if (false === $res) {
				return;
			}
			echo \sprintf('Loading "%s"', $fpdfFilename);
			$response = $client->request('GET', $uri);
			foreach($client->stream($response) as $chunk) {
				\fwrite($res, $chunk->getContent());
				echo '.';
			}
			echo '[OK]';
			\fclose($res);
			echo \PHP_EOL;			
		}
		
		//###> Extract .zip to vendor
		if (!\is_dir($extractToDir)) {
			echo \sprintf('Extracting "%s"...', $fpdfFilename);
			$zip = new \ZipArchive();
			if (true === $zip->open($absDownloadPath)) {
				$zip->extractTo($extractToDir);
				$zip->close();
				echo '[OK]';
			} else {
				echo '[EXTRACTING ERROR]';
			}
			echo \PHP_EOL;
		}
	}
}