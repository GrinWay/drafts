<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type as FormType;

class ChatFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('message', FormType\TextType::class, options: [
				'label' => false,
				'attr' => [
					'data-action' => 'keydown.enter->html-listener#_:prevent',
					'autofocus' => 'autofocus',
					'autocomplete' => 'off',
				],
			])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
			'attr' => [
				'id' => 'main-live-chat',
			],
		]);
    }
}
