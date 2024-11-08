<?php

namespace App\Test;

class UsualClass
{
	public function __construct(
		public readonly string $secret,
		private ?string $author,
	) {
	}
	
	public function getAuthor(): ?string {
		return $this->author;
	}
	
	public function setAuthor($author): static {
		$this->author = $author;
		return $this;
	}
}