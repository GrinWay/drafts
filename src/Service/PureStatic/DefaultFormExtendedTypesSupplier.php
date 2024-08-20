<?php

namespace App\Service\PureStatic;

use Symfony\Component\Form\Extension\Core\Type as FormType;
use Symfony\Component\Form\AbstractType;

class DefaultFormExtendedTypesSupplier
{
	public static function supply(): iterable {
		yield FormType\FormType::class;
	}
}