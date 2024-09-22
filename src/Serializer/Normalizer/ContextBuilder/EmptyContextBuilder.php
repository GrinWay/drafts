<?php

namespace App\Serializer\Normalizer\ContextBuilder;

use Symfony\Component\Serializer\Context\ContextBuilderInterface;
use Symfony\Component\Serializer\Context\ContextBuilderTrait;

class EmptyContextBuilder implements ContextBuilderInterface
{
	use ContextBuilderTrait;
	
	public const EMPTY_CASTING_KEY = 'casting_empty_to_null';
	
	/**
	 * 
	 */
	public function withCastingEmptyToNull(bool $isCasting): static {
		return $this->with(self::EMPTY_CASTING_KEY, $isCasting);
	}
}