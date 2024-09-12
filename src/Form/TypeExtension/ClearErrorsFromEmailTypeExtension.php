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
use Symfony\Component\Validator\Constraints;

class ClearErrorsFromEmailTypeExtension extends AbstractTypeExtension
{
    public static function getExtendedTypes(): iterable
    {
        yield FormType\EmailType::class;
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        return;
        $view->vars['errors'] = [];
        $view->vars['valid'] = true;
    }
}
