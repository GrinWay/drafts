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

class TestLargeFormType extends AbstractFormType
{
    public function __construct(
        PropertyAccessorInterface $pa,
    ) {
        parent::__construct(
            pa: $pa,
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'house_number_styled' => false,
                'address_list' => null,
            ])
            ->setAllowedTypes('address_list', ['array', 'null'])
        ;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $addressList = $options['address_list'];
        $addressOptions = ['help' => 'Help info: this is the address'];

        if (null !== $addressList) {
            $builder
            ->add(
                'address',
                FormType\ChoiceType::class,
                options: [
                    ...$addressOptions,
                    'choices' => $addressList,
                ]
            );
        } else {
            $builder
            ->add(
                'address',
                FormType\TextType::class,
                options: [
                    ...$addressOptions,
                ]
            );
        }

        $houseNumberOptions = [
            'label' => 'House number',
        ];
        if (true === $options['house_number_styled']) {
            $houseNumberOptions['block_prefix'] = 'styled_theme';
        }

        $builder
            ->add(
                'car',
                FormType\TextType::class,
                options: [
                    'attr' => [
                        'class' => 'w-100 mb-3',
                        'style' => 'display: inline-block;',
                    ],
                ]
            )
            ->add(
                'house_number',
                FormType\TextType::class,
                options: $houseNumberOptions
            )
            ->add(
                'descriptionForOld',
                FormType\TextareaType::class,
                options: [
                    'block_name' => 'description_for_old',
                    'block_prefix' => 'styled_theme',
                ]
            )
        ;
    }
}
