<?php

namespace App\Security\Badge;

class ModifyUserPropBadge {
	
	private readonly \Closure $modifiedPropCallable;
	
	//TODO: current
	public function __construct(
		private readonly string $propertyAccessPath,
		callable $modifiedPropCallable,
	) {
		$this->modifiedPropCallable = \Closure::fromCallable($modifiedPropCallable);
	}
}