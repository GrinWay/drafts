<?php

namespace App\Form\Type;

use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Form\FormEvents;
use App\Entity\Media\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type as FormType;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class ImageFormType extends AbstractFormType
{
    public function __construct(
		PropertyAccessorInterface $pa,
		private readonly string $absPublicDir,
	) {
		parent::__construct(
			pa: $pa,
		);
	}
	
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id',// FormType\FileType::class,
				options: [
					'disabled' => true,
				]
			)
            ->add('file', VichImageType::class,
				options: [
				]
			)
			->setMethod('PATCH')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Image::class,
        ]);
    }
}
