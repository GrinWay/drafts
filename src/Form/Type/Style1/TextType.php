<?php

namespace App\Form\Type\Style1;

use Symfony\Component\Form\Extension\Core\Type as FormType;

class TextType extends AbstractFormType
{	
    public function getParent()
    {
        return FormType\TextType::class;
    }
}
