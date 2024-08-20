<?php

namespace App\Composer\Scripts;

class PrePackageInstall
{
	public static function execute($event): void {
		
		\var_dump($event->getArguments());
		
		echo __METHOD__.\PHP_EOL;
	}
}