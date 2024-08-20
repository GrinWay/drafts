<?php

namespace App\Translation;

use Symfony\Component\Translation\TranslatableMessage as SymfonyTranslatableMessage;

class TranslatableMessage extends SymfonyTranslatableMessage
{
	private ?string $domain;
	
	public function setDomain(?string $domain): static {
		$this->domain = $domain;
		return $this;
	}
	
    public function getDomain(): ?string
    {
        return $this->domain;
    }
}