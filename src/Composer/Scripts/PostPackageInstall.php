<?php

namespace App\Composer\Scripts;

use Composer\Script\Event;
use Composer\Installer\PackageEvent;

class PostPackageInstall
{
	public static function execute(PackageEvent $event): void {
		echo (string) $event->getOperation().\PHP_EOL;
	}
}