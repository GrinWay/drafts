<?php

namespace App\Form\Type;

use function Symfony\component\string\u;

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
	public function __construct(
		PropertyAccessorInterface $pa,
	) {
		parent::__construct(pa: $pa);
	}
	
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
			'csrf_protection' => null,
			'csrf_field_name' => null,
			'csrf_token_id' => null,
        ]);
	}
	
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
		$otf = static function ($model) {
			//\dump('VIEW TRANS to FORM');
			return $model;
		};
		$fto = static function ($formFieldData) {
			//\dump('VIEW TRANS to OBJ');
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
				//\dump('SUBMIT');
			})
        ;
    }
	
	public function buildView(FormView $view, FormInterface $form, array $options)
    {
		foreach([
			[
				'optionsKey' => 'csrf_protection',
				'twigKey' => 'isCsrfProtectionEnabled',
			],
			[
				'optionsKey' => 'csrf_field_name',
				'twigKey' => 'csrfParameterName',
			],
			[
				'optionsKey' => 'csrfTokenId',
				'twigKey' => 'csrf_token_id',
			],
		] as [
			'optionsKey' => $optionsKey,
			'twigKey' => $twigKey,
		]) {
			if (null !== $value = $this->pa->getValue($options, (string) u($optionsKey)->ensureStart('[?')->ensureEnd(']'))) {
				$view->vars[$twigKey] = $value;
			}			
		}
    }
	
	public function getBlockPrefix(): string {
		return '';
	}
}
