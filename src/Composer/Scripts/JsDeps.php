<?php

namespace App\Composer\Scripts;

class JsDeps
{
	//###< ADD HERE ###
	const NODE_MODULES_PACK_NAMES = [
		'Chart.BarFunnel.js',
	];
	//###< ADD HERE ###

	const NODE_MODULES_DIR = 'node_modules';

    public static function load(): void
    {
		$exec = \sprintf('echo "%s"', self::class);
		$echoPackNames = \sprintf('executed to load %s packs:', self::NODE_MODULES_DIR);
		
		foreach(self::NODE_MODULES_PACK_NAMES as $packName) {
			$nodeModulesPack = \sprintf('%s/%s', self::NODE_MODULES_DIR, $packName);
			if (!\is_dir($nodeModulesPack)) {
				$echoPackNames .= \sprintf('%s"%s"', \PHP_EOL, $packName);
				$exec .= ' && ' . \sprintf('git clone https://github.com/chartjs/%s.git %s -q', $packName, $nodeModulesPack);
			}			
		}
		\exec($exec);
		echo $echoPackNames.\PHP_EOL;
    }
}
