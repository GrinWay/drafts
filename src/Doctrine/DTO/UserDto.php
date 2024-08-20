<?php

namespace App\Doctrine\DTO;

class UserDto implements \ArrayAccess {
	
	private mixed $range = null;
	
	public function __construct(
		private ?int $id = null,
		private ?string $name = null,
		private ?int $age = null,
	) {}
	
	public function getId(): mixed {
		\dump(__METHOD__);
		return $this->id;
	}
	
	public function setId(mixed $data): static {
		\dump(__METHOD__);
		$this->id = $data;
		return $this;
	}
	
	public function getName(): mixed {
		\dump(__METHOD__);
		return $this->name;
	}
	
	public function __clone()
    {
		\dump(__METHOD__);
	}
	
	public function setName(mixed $data): static {
		\dump(__METHOD__);
		$this->name = $data;
		return $this;
	}
	
	public function getAge(): mixed {
		\dump(__METHOD__);
		return $this->age;
	}
	
	public function setAge(mixed $data): static {
		\dump(__METHOD__);
		$this->age = $data;
		return $this;
	}
	
	public function offsetExists(mixed $offset): bool {
		\dump(__METHOD__);
		return isset($this->{$offset});
	}
	
	public function offsetGet(mixed $offset): mixed {
		\dump(__METHOD__);
		return $this->{$offset};
	}
	
	public function offsetSet(mixed $offset, mixed $value): void {
		\dump(__METHOD__);
		$this->{$offset} = $value;
	}
	
	public function offsetUnset(mixed $offset): void {
		\dump(__METHOD__);
		unset($this->{$offset});
	}
}