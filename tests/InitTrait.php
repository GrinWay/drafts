<?php

namespace App\Tests;

use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Console\Messenger\RunCommandMessage;

trait InitTrait {
	protected function init(
		bool $isDeleteCache = true,
		bool $isRebuildDb = true,
	): void {
		if (true === $isDeleteCache) {
			(new Filesystem())->remove(__DIR__.'/../var/cache/test');
		}
		
		$bus = static::getContainer()->get(MessageBusInterface::class);
		if (null !== $bus && true === $isRebuildDb) {			
			foreach([
				[
					'doctrine:database:drop',
					'--env=test',
					'-f',
					'-q',
				],
				[
					'doctrine:database:create',
					'--env=test',
					'-q',
				],
				[
					'doctrine:schema:create',
					'--env=test',
					'-q',
				],
				[
					'doctrine:fixture:load',
					'--env=test',
					'-q',
				],
			] as $command) {
				$bus->dispatch(new RunCommandMessage(\implode(' ', $command)));			
			}
		}
    }
}