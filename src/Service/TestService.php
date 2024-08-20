<?php

namespace App\Service;

class TestService
{
	public function __construct(
		public string $value = 'value',
	) {}
	
	public function set(): static {
		return $this;
	}
	
	public function get(string|int $key): mixed {
		return $key;
	}
}