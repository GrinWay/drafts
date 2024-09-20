<?php

namespace App\Form\Type;

use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraints;
use App\Entity\User;
use App\Entity\Product;
use App\Entity\UserPassport;
use Symfony\Component\Form\Extension\Core\Type as FormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Form\Type\Style1;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use App\Form\Type\ProductFormType;
use Symfony\Component\Serializer\Normalizer\NormalizableInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class UserType extends AbstractFormType implements NormalizableInterface
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('products', FormType\CollectionType::class, [
                'entry_type' => ProductFormType::class,

                'allow_add' => true,
                'allow_delete' => true,
                /**
                * Guarantee that OUR ->remove<Item>() will be called
                *
                * instead of working with collection directly
                * ->getCollection()->removeElement($item)
                */
                'by_reference' => false,
            ])
            ->setMethod('PATCH')
            /*
            ->add('passport', EntityType::class, [
                'class' => UserPassport::class,
                'choice_label' => 'id',
            ])
            */
        ;
    }
	
	public function normalize(NormalizerInterface|null $normalizer, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null {
		return 'normalized';
	}
}
