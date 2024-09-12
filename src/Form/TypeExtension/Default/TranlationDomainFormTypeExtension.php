<?php

namespace App\Form\TypeExtension\Default;

use App\Translation\TranslatableMessage;
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

// TODO: TranlationDomainFormTypeExtension (через конфиг default_translation_domain)
class TranlationDomainFormTypeExtension extends AbstractTypeExtension
{
    public const DOMAIN = 'app.form+intl-icu';

    public function __construct(
        private readonly PropertyAccessorInterface $pa,
    ) {
    }

    public static function getExtendedTypes(): iterable
    {
        return DefaultFormExtendedTypesSupplier::supply();
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'translation_domain' => self::DOMAIN,
            ])
        ;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $label = $this->pa->getValue($options, '[label]');

        if ($label instanceof TranslatableMessage) {
            $label->setDomain(self::DOMAIN);
        }
    }
}
