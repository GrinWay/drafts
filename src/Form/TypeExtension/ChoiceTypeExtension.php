<?php

namespace App\Form\TypeExtension;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type as FormType;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

class ChoiceTypeExtension extends AbstractTypeExtension {
	
	public function __construct (
	) {}
	
	public static function getExtendedTypes(): iterable {
		yield FormType\ChoiceType::class;
	}
	
	public function configureOptions(OptionsResolver $resolver): void
    {
		$resolver
			->setDefined([
				'selected_value',
			])
			->setAllowedTypes('selected_value', ['string', 'int', 'null'])
		;
    }
	
	public function buildView(FormView $view, FormInterface $form, array $options): void
    {
		if (isset($options['selected_value'])) {
			$view->vars['selected_value'] = $options['selected_value'];			
		}
    }
}