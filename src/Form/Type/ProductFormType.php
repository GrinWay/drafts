<?php

namespace App\Form\Type;

use function Symfony\Component\String\u;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type as FormType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;
use App\Entity\Task;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Type\Product\ProductTypes;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use App\Entity\Product\Product;

class ProductFormType extends AbstractFormType
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
		$productTypes = (new \ReflectionClass(ProductTypes::class))->getConstants();
		
		$builder
			->add('id',
				options: [
					'disabled' => true,
				],
			)
			->add('name')
			->add('description')
			->add('type', FormType\ChoiceType::class,
				options: [
					'mapped' => false,
					'choices' => $productTypes,
				],
			)
		;
	}

    public function configureOptions(OptionsResolver $resolver)
    {

        $resolver
            ->setDefaults([
				'data_class' => Product::class,
			])
        ;
    }
}
