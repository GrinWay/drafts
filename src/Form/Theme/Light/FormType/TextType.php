<?php

namespace App\Form\Theme\Light\FormType;

use Symfony\Component\Form\Form;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type as FormType;
use Symfony\Component\Validator\Constraints;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class TextType extends AbstractType
{
    protected function getThemeType(): string
    {
        return 'text';
    }

    public function getParent()
    {
        return FormType\TextType::class;
    }
}
