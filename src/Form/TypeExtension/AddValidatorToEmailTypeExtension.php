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
use Symfony\Component\Validator\Constraints;

class AddValidatorToEmailTypeExtension extends AbstractTypeExtension {
	
	public function __construct (
	) {}
	
	public static function getExtendedTypes(): iterable {
		yield FormType\EmailType::class;
	}
	
	public function configureOptions(OptionsResolver $resolver): void
    {
		$resolver
			->setDefaults([
				'constraints' => [
					new Constraints\Email(),
				],
			])
		;
    }
	
}