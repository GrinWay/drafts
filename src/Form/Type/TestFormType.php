<?php

namespace App\Form\Type;

use Symfony\Component\Validator\Constraints;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type as FormType;
use App\Entity\Product\ToyProduct;
use App\Service\StringService;
use App\Service\Form\TestForTestFormType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Translation\TranslatableMessage;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use App\Type\Note\NoteType;
use Symfony\Component\Form\FormEvents;

class TestFormType extends AbstractFormType
{
    public function __construct(
		PropertyAccessorInterface $pa,
		private readonly StringService $stringService,
	) {
		parent::__construct(
			pa: $pa,
		);
	}
	
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add($builder->create('name', FormType\ChoiceType::class,
				options: [
					'choices' => NoteType::TYPES,
					'multiple' => true,
				])
			)
            ->add('timezone', FormType\TextType::class,
				options: [
			])
            ->add('product', ProductFormType::class,
				options: [
					'data' => new ToyProduct(description: '~s', name: 'aadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadaaadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadadada'),
				]
			)
            ->add('price', FormType\NumberType::class,
				options: [
			])
            ->add('email', FormType\EmailType::class,
				options: [
			])
            ->add('createdAt', FormType\DateTimeType::class,
				options: []
			)
            ->add('rubric', //FormType\FileType::class,
				options: [
				]
			)
			/*
            ->add($builder->create('compound', TestLargeFormType::class,
				options: [
					'mapped' => false,
				])
				//->addViewTransformer(new CallbackTransformer(static fn($v) => $v, static fn($v) => ['address' => '', 'car' => '']))
				//->addModelTransformer(new CallbackTransformer(static fn($v) => $v, static fn($v) => $v))
			)
			*/
			->setMethod('PATCH')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
		//doesn't work
		$constraints = [
			'price' => new Constraints\NotBlank(groups: null),
		];
		
        $resolver->setDefaults([
			'data_class' => TestForTestFormType::class,
			//for parent
			/*
			'error_mapping' => [
				'upperCaseName' => 'name',
				// the rest errors to ...
				'.' => 'price',
			],
			'constraints' => $constraints,
			*/
        ]);
    }
}
