<?php

namespace App\Form\Type;

use function Symfony\component\string\u;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use App\Doctrine\DTO\UserDto;
use App\Repository\AvatarRepository;
use Symfony\Component\Validator\Constraints;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Form\FormEvents;
use App\Entity\Media\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type as FormType;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Form\CallbackTransformer;

class HandyDateFilterFormType extends AbstractFormType
{
    public function __construct(
        PropertyAccessorInterface $pa,
    ) {
        parent::__construct(
            pa: $pa,
        );
    }

	public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
			//->add('myPoly', FormType\TextType::class)
		;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
			->setDefaults([
				'choices' => [
					'Today (date only)' => 'today_date',
					'Today (hour and minute)' => 'today_date_time',
				],
			])
        ;
    }
	
	public function getParent()
    {
        return ChoiceType::class;
    }
}
