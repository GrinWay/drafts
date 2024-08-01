<?php

namespace App\Messenger\Stamp;

use Symfony\Component\Messenger\Stamp\StampInterface;

class StopPropagationStamp implements StampInterface {
	
	public function __construct(
		private bool $enabled = false,
	) {
	}
	
	public function enable(): static {
		$this->enabled = true;
		
		return $this;
	}
	
	public function disable(): static {
		$this->enabled = false;

		return $this;
	}
	
	public function isEnabled(): bool {
		return true === $this->enabled;
	}
}