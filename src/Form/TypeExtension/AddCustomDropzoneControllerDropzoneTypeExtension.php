<?php

namespace App\Form\TypeExtension;

use Symfony\UX\Dropzone\Form\DropzoneType;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type as FormType;
use App\Form\DataTransformer\NormalizeLocaleDataTransformer;

class AddCustomDropzoneControllerDropzoneTypeExtension extends AbstractTypeExtension
{
    public function __construct()
    {
    }

	public function buildView(FormView $view, FormInterface $form, array $options): void
    {
		$view->vars['attr'] = \array_merge(
			$view->vars['attr'],
			[
				'placeholder' => '➕',
			],
		);
	}

    public function configureOptions(OptionsResolver $resolver): void
    {
		$resolver
			//->setDefaults([])
		;
    }

    public static function getExtendedTypes(): iterable
    {
        yield DropzoneType::class;
    }
}
