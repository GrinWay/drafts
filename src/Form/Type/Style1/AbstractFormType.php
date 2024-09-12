<?php

namespace App\Form\Type\Style1;

use App\Form\Type\AbstractFormType as AppAbstractFormType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AbstractFormType extends AppAbstractFormType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'style1' => true,
        ]);

        parent::configureOptions($resolver);
    }
}
