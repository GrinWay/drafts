<?php

namespace App\Form\TypeExtension;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type as FormType;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

class BadgeChoiceTypeExtension extends AbstractTypeExtension
{
    public function __construct(
        private readonly PropertyAccessorInterface $pa,
    ) {
    }

    public static function getExtendedTypes(): iterable
    {
        yield FormType\ChoiceType::class;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'display_count' => false,
            ])
            ->setAllowedTypes('display_count', 'bool')
        ;
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        if (false !== ($count = $this->pa->getValue($options, '[display_count]'))) {
            $choices = $this->pa->getValue($options, '[choices]');
            $choices ??= [];
            $view->vars['app_count'] = \count($choices);
        }
    }
}
