<?php

namespace App\Form\Type;

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

class RegistrationFormType extends AbstractFormType
{
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
            ->add('password', FormType\RepeatedType::class, [
				'type' => FormType\PasswordType::class,
				'mapped' => false,
				// instead of being set onto the object directly,
                // this is read and encoded in the controller
                'first_options' => [
					'label' => 'Пароль',
					'hash_property_path' => 'password',
					'attr' => [
						'autocomplete' => 'new-password',
					],
					'constraints' => [
						new NotBlank([
							'message' => 'Please enter a password',
						]),
						new Length([
							'min' => 6,
							'minMessage' => 'Your password should be at least {{ limit }} characters',
							// max length allowed by Symfony for security reasons
							'max' => 4096,
						]),
					],
				],
				'second_options' => [
					'label' => 'Повтори пароль',
				],
            ])
            ->add('agreeTerms', CheckboxType::class, [
				'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Согласись.',
                    ]),
                ],
            ])
            ->add('submit', FormType\SubmitType::class, [])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'csrf_token_id' => 'register-a-new-user',
			/*
			*/
        ]);
    }
}
