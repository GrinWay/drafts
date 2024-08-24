<?php

namespace App\Form\Type;

use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Event as FormEvent;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraints;
use App\Entity\User;
use App\Entity\Product;
use App\Entity\UserPassport;
use Symfony\Component\Form\Extension\Core\Type as FormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Form\Type\Style1;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use App\Form\Type\ProductFormType;

class Auth2FAFormType extends AbstractFormType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
        ]);
	}
	
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
		$otf = static function ($model) {
			\dump('VIEW TRANS to FORM');
			return $model;
		};
		$fto = static function ($formFieldData) {
			\dump('VIEW TRANS to OBJ');
			return $formFieldData;
		};
        
		$builder
            ->add($builder->create('_auth_code', FormType\TextType::class, 
				options: [
					'label' => 'Код аутентификации:',
					'row_attr' => [
					],
					'attr' => [
						'id' => '_auth_code',
						'autocomplete' => 'one-time-code',
					],
					'label_attr' => [
						'class' => '',
					],
				])
			)
			->addViewTransformer(new CallbackTransformer($otf, $fto))
			->addEventListener(FormEvents::SUBMIT, static function (FormEvent\SubmitEvent $event): void {
				\dump('SUBMIT');
			})
        ;
    }
	
	public function getBlockPrefix(): string {
		return '';
	}
}
