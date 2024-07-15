<?php

namespace App\Contract\Form;

// TODO: PreventModifyingPropsOfEntity
class PreventModifyingPropsOfEntity implements \Iterator {
	
	private readonly array $forbiddenProperties;
	private int $k;
	
	public function __construct(
		string...$forbiddenProperties,
	) {
		$this->forbiddenProperties = $forbiddenProperties;
		$this->k = 0;
	}
	
	public function current(): mixed {
		return $this->forbiddenProperties[$this->k];
	}
	
	public function key(): mixed {
		return $this->k;
	}
	
	public function next(): void {
		++$this->k;
	}
	
	public function rewind(): void {
		$this->k = 0;
	}
	
	public function valid(): bool {
		return isset($this->forbiddenProperties[$this->k]);
	}
	
}