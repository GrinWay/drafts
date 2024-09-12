<?php

namespace App\Form\TypeExtension\Default;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type as FormType;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraints;
use App\Service\PureStatic\DefaultFormExtendedTypesSupplier;

// TODO: LabelFormatFormTypeExtension (через конфиг default_label_format)
class LabelFormatFormTypeExtension extends AbstractTypeExtension
{
    public function __construct()
    {
    }

    public static function getExtendedTypes(): iterable
    {
        return DefaultFormExtendedTypesSupplier::supply();
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'label_format' => '%name%',
            ])
        ;
    }
}
