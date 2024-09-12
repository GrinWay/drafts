<?php

namespace App\Form\Type;

use function Symfony\Component\String\u;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type as FormType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;
use App\Entity\Task;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Type\Product\ProductTypes;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class StyledChoiceType extends AbstractType
{
    public function __construct(
        PropertyAccessorInterface $pa,
    ) {
        parent::__construct(
            pa: $pa,
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'choices' => [
                    'A' => '1',
                    'B' => '2',
                    'C' => '3',
                ],
            ])
        ;
    }

    public function getParent(): ?string
    {
        return FormType\ChoiceType::class;
    }

    /*
    public function getBlockPrefix(): string
    {
        return 'styled_theme';
    }
    */
}
