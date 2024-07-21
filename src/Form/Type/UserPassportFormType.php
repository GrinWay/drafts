<?php

namespace App\Form\Type;

use App\Entity\User;
use App\Entity\UserPassport;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserPassportFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',
				options: [
					'attr' => [
						'autofocus' => 'autofocus',
					],
					'translation_domain' => 'app.security+intl-icu',
				]
			)
            ->add('lastName',
				options: [
					'translation_domain' => 'app.security+intl-icu',
				]
			)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserPassport::class,
			'label_format' => '%name%',
        ]);
    }
}
