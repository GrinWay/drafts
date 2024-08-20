<?php

namespace App\Service;

use GrinWay\Service\Service\CarbonService as GrinWayCarbonService;
use Carbon\Carbon;
use Carbon\CarbonImmutable;

class CarbonService extends GrinWayCarbonService
{
	public static function throwIfNotTypeByString(string $carbonClass): void {
		$isSubclassOfCarbon = \is_subclass_of($carbonClass, Carbon::class) || \is_subclass_of($carbonClass, CarbonImmutable::class);
		$isEqualToClass = Carbon::class === $carbonClass || CarbonImmutable::class === $carbonClass;
		
		if ($isSubclassOfCarbon || $isEqualToClass) return;
		
		$m = \sprintf(
			'The passed string: "%s" must be equal or to be subclass of "%s" or "%s"',
			$carbonClass,
			Carbon::class,
			CarbonImmutable::class,
		);
		throw new \Exception($m);
	}
}
