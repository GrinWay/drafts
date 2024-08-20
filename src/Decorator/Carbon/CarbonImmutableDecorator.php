<?php

namespace App\Decorator\Carbon;

use Carbon\CarbonImmutable;

class CarbonImmutableDecorator extends CarbonImmutable {
	
	public function __sleep(): array {
		parent::__sleep();
		
		\dump(__METHOD__);
	}

	public function __wakeup(): void {
		parent::__wakeup();
		
		\dump(__METHOD__);
	}
	
	public function __serialize(): array {
		\dump(__METHOD__);
		
		return parent::__serialize();
	}
	
	public function __unserialize(array $data): void {
		\dump(__METHOD__);
		
		parent::__unserialize();
	}
	
}