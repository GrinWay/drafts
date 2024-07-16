<?php

namespace App\Type\Product;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum FurnitureProductColorType: string implements TranslatableInterface
{
    case BLACK = 'black';
    case WHITE = 'white';
	
	public function trans(TranslatorInterface $translator, ?string $locale = null): string {
		$id = \sprintf('app.%s', \strtolower($this->name));
		return $translator->trans($id, [], 'form', $locale);
	}
}
