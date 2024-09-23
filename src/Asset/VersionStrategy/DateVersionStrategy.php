<?php

namespace App\Asset\VersionStrategy;

use Symfony\Component\Asset\VersionStrategy\VersionStrategyInterface;
use Carbon\Carbon;

class DateVersionStrategy implements VersionStrategyInterface
{
	public function __construct() {}
	
    /**
     * Returns the asset version for an asset.
     */
    public function getVersion(string $path): string
	{
		return Carbon::now('UTC')->format('Ymd');
	}

    /**
     * Applies version to the supplied path.
     */
    public function applyVersion(string $path): string
	{
		return \sprintf('%s?version=%s', $path, $this->getVersion($path));
	}
}