<?php

namespace App\Form\Theme\Light\FormType;

use Symfony\Component\Form\AbstractType as SymfonyAbstractType;

abstract class AbstractType extends SymfonyAbstractType
{
    public function getBlockPrefix()
    {
        return 'light_' . $this->getThemeType();
    }

    abstract protected function getThemeType(): string;
}
