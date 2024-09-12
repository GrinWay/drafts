<?php

namespace App\Form\Type;

use Symfony\Component\Form\Form;
use Carbon\CarbonImmutable;
use Carbon\Carbon;
use Symfony\Component\Form\CallbackTransformer;
use App\Form\Type\Style1;
use Symfony\Component\Form\Extension\Core\Type as FormType;
use App\Entity\Product\FurnitureProduct;
use App\Entity\ProductPassport;
use App\Entity\User;
use Symfony\Component\Validator\Constraints;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Form\DataMapper\YearMonthDayHourMinuteSecondDataMapper;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use App\Service\CarbonService;
use Symfony\Contracts\Service\Attribute\Required;

class YearMonthDayHourMinuteSecondType extends AbstractFormType
{
    public function __construct(
        PropertyAccessorInterface $pa,
        private readonly YearMonthDayHourMinuteSecondDataMapper $yearMonthDayHourMinuteSecondDataMapper,
    ) {
        parent::__construct(
            pa: $pa,
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $carbonNormalizer = static function (OptionsResolver $_, $value) {
            CarbonService::throwIfNotTypeByString($value);
            return $value;
        };

        $resolver
            ->setDefaults([
                'empty_data' => null,
                'carbon_class' => CarbonImmutable::class,
            ])
            ->addNormalizer('carbon_class', $carbonNormalizer)
        ;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'year',
                FormType\IntegerType::class,
                options: [
                    'constraints' => [
                        new Constraints\NotBlank(groups: ['date']),
                        new Constraints\Type('integer', groups: ['date']),
                        new Constraints\Positive(groups: ['date']),
                    ],
                ],
            )
            ->add(
                'month',
                FormType\IntegerType::class,
                options: [
                    'constraints' => [
                        //new Constraints\NotBlank(groups: ['date']),
                        new Constraints\Type('integer', groups: ['date']),
                        new Constraints\Positive(groups: ['date']),
                        new Constraints\Range(min: 1, max: 12, groups: ['date']),
                    ],
                ],
            )
            ->add(
                'day',
                FormType\IntegerType::class,
                options: [
                    'constraints' => [
                        //new Constraints\NotBlank(groups: ['date']),
                        new Constraints\Type('integer', groups: ['date']),
                        new Constraints\Positive(groups: ['date']),
                        new Constraints\Range(min: 1, max: 31, groups: ['date']),
                    ],
                ],
            )
            ->add(
                'hour',
                FormType\IntegerType::class,
                options: [
                    'constraints' => [
                        //new Constraints\NotBlank(groups: ['time']),
                        new Constraints\Type('integer', groups: ['time']),
                        new Constraints\Positive(groups: ['time']),
                        new Constraints\Range(min: 0, max: 24, groups: ['time']),
                    ],
                ],
            )
            ->add(
                'minute',
                FormType\IntegerType::class,
                options: [
                    'constraints' => [
                        //new Constraints\NotBlank(groups: ['time']),
                        new Constraints\Type('integer', groups: ['time']),
                        new Constraints\Positive(groups: ['time']),
                        new Constraints\Range(min: 0, max: 60, groups: ['time']),
                    ],],
            )
            ->add(
                'second',
                FormType\IntegerType::class,
                options: [
                    'constraints' => [
                        //new Constraints\NotBlank(groups: ['time']),
                        new Constraints\Type('integer', groups: ['time']),
                        new Constraints\Positive(groups: ['time']),
                        new Constraints\Range(min: 0, max: 60, groups: ['time']),
                    ],
                ],
            )
            ->setDataMapper(
                $this->yearMonthDayHourMinuteSecondDataMapper
                ->setCarbonClass($options['carbon_class'])
            )
        ;
    }
}
