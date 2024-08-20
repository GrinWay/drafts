<?php

namespace App\Form\Type;

use function Symfony\Component\String\u;

use App\Form\DataTransformer\ForeignKeyAsTextIdDataTransformer;
use App\Form\DataTransformer\ObjHasDateTimeFormHasTextDataTrasformer;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type as FormType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;
use App\Entity\Task;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class DateTimeAsTextType extends AbstractFormType
{
    public function __construct(
		PropertyAccessorInterface $pa,
        private readonly ObjHasDateTimeFormHasTextDataTrasformer $objHasDateTimeFormHasTextDataTrasformer,
    ) {
		parent::__construct(
			pa: $pa,
		);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
		$builder
			->addModelTransformer($this->objHasDateTimeFormHasTextDataTrasformer)
		;
    }
	
	public function getParent(): ?string {
		return FormType\TextType::class;
	}
}
