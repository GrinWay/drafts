<?php

namespace App\Form\TypeExtension;

use Symfony\UX\Cropperjs\Form\CropperType;
use Symfony\UX\Dropzone\Form\DropzoneType;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type as FormType;
use App\Form\DataTransformer\NormalizeLocaleDataTransformer;

class AddCustomCropperControllerDropzoneTypeExtension extends AbstractTypeExtension
{
    public function __construct()
    {
    }

	public function buildView(FormView $view, FormInterface $form, array $options): void
    {
		//\dump($view->vars['attr'] ?? null);
		//'=""'
	}

    public function configureOptions(OptionsResolver $resolver): void
    {
		$resolver
			//->setDefaults([])
		;
    }

    public static function getExtendedTypes(): iterable
    {
        yield CropperType::class;
    }
}
