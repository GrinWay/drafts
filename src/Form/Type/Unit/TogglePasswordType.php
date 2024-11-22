<?php

namespace App\Form\Type\Unit;

use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type as FormType;

class TogglePasswordType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
			'toggle' => true,
			'hidden_label' => '<span class="ms-1 fs-8">Скрыть</span>',
			'visible_label' => '<span class="ms-1 fs-8">Показать</span>',
			'visible_icon' => '<i class="text-dark bi bi-emoji-expressionless"></i>',
			'hidden_icon' => '<i class="text-dark bi bi-emoji-dizzy"></i>',
		]);
    }

	public function getParent() {
		return FormType\PasswordType::class;
	}
}
