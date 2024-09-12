<?php

namespace App\Form\Type;

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

class DeleteUserFormType extends AbstractFormType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'password',
                FormType\PasswordType::class,
                options: [
                    'label' => 'Пароль',
                    'attr' => [
                        'autofocus' => 'autofocus',
                        'placeholder' => '***',
                    ],
                    'constraints' => [
                        new Constraints\NotBlank(),
                    ],
                ],
            )
        ;
    }
}
