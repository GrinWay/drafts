<?php

use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\ErrorHandler\ErrorHandler;

$__DIR__ = __DIR__;

require dirname($__DIR__).'/vendor/autoload.php';

\set_exception_handler([new ErrorHandler(), 'handleException']);

//###> Must be at least 1 driver ###
$pathToDriver = "${__DIR__}/../drivers";
\exec("cd ${pathToDriver} && ls | wc -l", output: $result);
if (isset($result[0]) && (!\is_numeric($result[0]) || 1 > $result[0])) {
	\exec("php \"${__DIR__}/../vendor/bin/bdi\" detect drivers");
}
//###< Must be at least 1 driver ###

if ($_SERVER['APP_CLEAR_CACHE']) {
	(new Filesystem())->remove($__DIR__.'/../var/cache/test');
	(new Filesystem())->remove($__DIR__.'/../var/cache/panther');
}

if ($_SERVER['APP_TRUNCATE_DB']) {
	foreach([
		[
			"php \"${__DIR__}/../bin/console\"",
			'doctrine:database:drop',
			'--env=test',
			'-f',
			'-q',
		],
		[
			"php \"${__DIR__}/../bin/console\"",
			'doctrine:database:create',
			'--env=test',
			'-q',
		],
		[
			"php \"${__DIR__}/../bin/console\"",
			'doctrine:schema:create',
			'--env=test',
			'-q',
		],
		[
			"php \"${__DIR__}/../bin/console\"",
			'doctrine:fixture:load',
			'--env=test',
			'-q',
		],
	] as $command) {
		\exec(\implode(' ', $command));			
	}
}

if (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname($__DIR__).'/.env');
}

if ($_SERVER['APP_DEBUG']) {
    umask(0000);
}