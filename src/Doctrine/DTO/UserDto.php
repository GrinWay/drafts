<?php

namespace App\Doctrine\DTO;

class UserDto {
	public function __construct(
		public readonly ?int $id = null,
		public readonly ?string $name = null,
		public readonly ?int $age = null,
	) {}
}