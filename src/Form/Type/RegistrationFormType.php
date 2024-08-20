<?php

namespace App\Form\Type;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type as FormType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use App\Validation\GroupProvider\Entity\UserGroupProvider;

class RegistrationFormType extends AbstractFormType
{
    public function __construct(
		PropertyAccessorInterface $pa,
		private readonly UserGroupProvider $userGroupProvider,
	) {
		parent::__construct(
			pa: $pa,
		);
	}
	
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('passport', UserPassportFormType::class, 
				options: [
					'attr' => [
					],
					'label' => false,
				],
			)
            ->add('email',// FormType\EmailType::class,
				options: [
				],
			)
            ->add('roles', 
				options: [
					'attr' => [
						'autocomplete' => 'on',
					],
					'getter' => static fn($obj, $f) => \implode(', ', $obj->getRoles()),
					'setter' => static fn($obj, $v, $f) => $obj->setRoles(empty($v) ? [] : \array_map(static fn($v) => \trim($v), \explode(',', $v))),
				],
			)
			/* RepeatedType
            ->add('password', FormType\RepeatedType::class, [
				'type' => FormType\PasswordType::class,
				'mapped' => false,
				// instead of being set onto the object directly,
                // this is read and encoded in the controller
                'first_options' => [
					'label' => 'Пароль',
					'hash_property_path' => 'password',
					'attr' => [
						'value' => $pass = '123123',
						'autocomplete' => 'new-password',
					],
					'constraints' => [
						new NotBlank([
							'message' => 'Please enter a password',
						], groups: [
							'register',
						]),
						new Length([
							'min' => 6,
							'minMessage' => 'Your password should be at least {{ limit }} characters',
							// max length allowed by Symfony for security reasons
							'max' => 10,
						], groups: [
							'register',
						]),
					],
				],
				'second_options' => [
					'label' => 'Повтори пароль',
					'attr' => [
						'value' => $pass,
					],
				],
            ])
			*/
			/* Only one poly of the password
			*/
            ->add('password', FormType\PasswordType::class, [
				'mapped' => false,
				'label' => 'Пароль',
				'hash_property_path' => 'password',
				//не читается
				//'data' => '123123',
				'attr' => [
					//'value' => $pass = '123123',
					'autocomplete' => 'new-password',
				],
				'constraints' => [
					new NotBlank([
						'message' => 'Please enter a password',
					], groups: [
						'register',
					]),
					new Length([
						'min' => 6,
						'minMessage' => 'Your password should be at least {{ limit }} characters',
						// max length allowed by Symfony for security reasons
						//'max' => 10,
					], groups: [
						'register',
					]),
				],
            ])
            ->add('agreeTerms', CheckboxType::class, [
				'mapped' => false,
				//'data' => true,
				'constraints' => [
                    new IsTrue([
                        'message' => 'Согласись.',
						'groups' => [
							'register',
						],
                    ]),
                ],
            ])
			/*
            ->add('_hiddenPoly', FormType\TextType::class, [
				'mapped' => false,
            ])
			*/
            ->add('submit', FormType\SubmitType::class, [
				'label' => 'Register',
			])
			->setMethod('POST')
        ;
    }
	
	public function finishView(FormView $view, FormInterface $form, array $options): void
    {
		\dump(
			\get_debug_type($form->getConfig()),
		);
	}

    public function configureOptions(OptionsResolver $resolver): void
    {
		//\dd($this->userGroupProvider);
        $resolver->setDefaults([
            'data_class' => User::class,
			'validation_groups' => $this->userGroupProvider,
            'csrf_token_id' => 'register-a-new-user',
			/*
			'allow_extra_fields' => true,
			*/
        ]);
    }
	
	public function getBlockPrefix(): string {
		return 'app_user_registration_form';
	}
}
