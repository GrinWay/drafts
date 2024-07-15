<?php

namespace App\Form\TypeExtension;

use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type as FormType;
use App\Form\DataTransformer\NormalizeLocaleDataTransformer;

class FormTypeExtension extends AbstractTypeExtension {
	
	public function __construct(
    ) {
    }
	
	public function configureOptions(OptionsResolver $resolver): void
    {
		$resolver
			->setDefaults([
				'translation_domain' => 'validators',
			])
		;
    }
	
	public static function getExtendedTypes(): iterable {
		yield FormType\FormType::class;
	}
}