<?php

namespace App\Composer\Scripts;

use Composer\Script\Event;

class PostAutoloadDump
{
	public static function execute(Event $event): void {
		$composer = $event->getComposer();
		$composerConfig = $composer->getConfig();
		
		$repositories = $composerConfig->all()['repositories'] ?? [];
		
		self::downloadFfmpegForThisOS();
	}
	
	private static function downloadFfmpegForThisOS(): void {
		echo __METHOD__.\PHP_EOL;
	}
}