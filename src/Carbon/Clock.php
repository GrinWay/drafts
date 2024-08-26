<?php

namespace App\Carbon;

use Carbon\CarbonImmutable;
use Psr\Clock\ClockInterface;

class ClockImmutable implements ClockInterface
{
	private readonly CarbonImmutable $carbonImmutable;
	
	public function __construct() {
		$this->carbonImmutable = new CarbonImmutable('UTC');
	}
	
	public function now(): \DateTimeImmutable {
		return $this->carbonImmutable->now();
	}
	
	public function __get(string $name): mixed {
		return $this->carbonImmutable->{$name};
	}
	
	public function __call(string $name, array $arguments): mixed {
		return $this->carbonImmutable->$name(...$arguments);
	}
	
	public static function __callStatic(string $name, array $arguments): mixed {
		return $this->carbonImmutable->$name(...$arguments);
	}
	
}