<?php

namespace App\Form\Type;

use function Symfony\component\string\u;
use function App\Resources\t;

use Symfony\Component\Form\Event as FormEvent;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Form\FormInterface;
use App\Messenger\Command\Message\OnlyWeekendsOfThisMonth;
use App\Messenger\Command\Message\Hours;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Translation\TranslatableMessage;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use App\Doctrine\DTO\UserDto;
use App\Repository\AvatarRepository;
use Symfony\Component\Validator\Constraints;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Form\FormEvents;
use App\Entity\Media\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type as FormType;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class ImageFormType extends AbstractFormType
{
    public function __construct(
        PropertyAccessorInterface $pa,
        private readonly string $absPublicDir,
        private readonly AvatarRepository $avatarRepo,
        private $get,
    ) {
        parent::__construct(
            pa: $pa,
        );
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $vtf = static function ($v) {
            //\dd($v);
            //$v->setId(2);
            \dump('vtf');
            return $v;
        };
        $vto = static function ($v) {
            \dump('vto');
            return $v;
        };
        $vt = new CallbackTransformer($vtf, $vto);

        $mtf = static function ($v) {
            \dump('mtf');
            return $v;
        };
        $mto = static function ($v) {
            \dump('mto');
            return $v;
        };
        $mt = new CallbackTransformer($mtf, $mto);

        $getter = function ($o) {
            \dump('getter');
            \dd($o);
            return $v;
        };
        $setter = function ($o, $v) {
            \dump('setter');
            \dd($o);
            return $v;
        };

        $hours = ($this->get)(new Hours(includePassed: false));

        $weekendDays = ($this->get)(new OnlyWeekendsOfThisMonth(includePassed: false));
        //$weekendDays = \range(1, 31);
        \array_walk($weekendDays, static fn(&$d) => $d = (string) $d);

        $days = array_combine(\range(1, 5), \range(1, 5));

        //\dd(Carbon::now('UTC')->tz('+12:00'));

        $builder
            ->add(
                'filepath', //FormType\TextType::class,
                options: [
					'attr' => [
						'autocomplete' => 'off',
					],
					'constraints' => [
						new Constraints\NotBlank(),
					],
                ],
            )
			/*
            ->add(
                'fileOriginalName', //FormType\TextType::class,
                options: [
                ],
            )
            ->add(
                'fileToken', //FormType\TextType::class,
                options: [
                    'block_name' => 'file_token',
                ],
            )
            ->add(
                'updatedAt', //FormType\ChoiceType::class,
                options: [
                    //'data' => new \DateTimeImmutable(),
                ],
            )
            ->add(
                'createdAt', //FormType\ChoiceType::class,
                options: [
                    //'data' => new \DateTimeImmutable(),
                ],
            )

            ->add(
                'id',
                FormType\UuidType::class,
                options: [
                    //'label' => t('created_count', ['{type}' => 'furniture', '{count}' => 2]),
                    'label' => t('created_count', ['type' => 'furniture', 'count' => 2]),
                    //'label' => 'created_count',
                    'mapped' => false,
                    'disabled' => true,
                ],
            )
            ->add(
                'userDto',
                UserDtoFormType::class,
                options: [
                    'help' => 'user_dto',
                    'help_translation_parameters' => [
                        '%class%' => UserDto::class,
                    ],
                    //'data' => new UserDto(id: 1, name: 'Alex', age: 22),
                ],
            )
            ->add($builder->create(
                'fileDimensions',
                FormType\CurrencyType::class, //OnlyCountryType::class,
                options: [
                    'label' => 'CHOICE TYPE',
                    'mapped' => false,
                    //'choices'  => 'fileDimensions',
                    //'getter' => $getter,
                    //'setter' => $setter,
                    //'only' => ['ru', 'us', 'jp'],
                    //'class' => \App\Type\Product\FurnitureProductColorType::class,
                    'choice_translation_locale' => 'ru',
                    //'intl' => true,
                    'choice_filter' => static function ($c) {
                        if (null === $c) {
                            return true;
                        }
                        return true;
                        $m = u($c)->ignoreCase()->match('~[0-9]~');
                        return 0 === count($m);
                    },
                    
                    //'required' => false,
                    //'placeholder' => 'app.placeholder.select_option',
                    'placeholder_attr' => [
                        'title' => 'Выбери',
                    ],
                    'preferred_choices' => ChoiceList::preferred(
                        $this,
                        static fn($c) => \is_string($c) ? u($c)->ignoreCase()->containsAny(['image']) : false,
                    ),
                    'separator_html' => true,
                    'separator' => '<hr>',

                    //'choices'  => \array_combine(['app.choice.image', 'app.choice.avatar'], \App\Type\Media\MediaType::TYPES),
                    //'choice_value' => static fn($v) => \is_array($v) ? \implode($v) : $v,

                    'duplicate_preferred_choices' => false,

                    'choice_translation_domain' => 'app.form',
                    'choice_translation_parameters' => [
                        'app.choice.image' => [],
                        'app.choice.avatar' => [
                            '%param%' => 'V',
                        ],
                    ],
                    //'choice_loader' => $this->avatarRepo->findAll(...),
                    //'choice_value' => static fn($v): string => $v?->id ?: '',
                    //'choice_label' => static fn($v): string => ($name = $v?->name) ? \mb_strtoupper($name) : $name,
                    // ChoiceList adds cache
                    //'placeholder' => null,
                    'attr' => [
                    ],
                    'constraints' => [
                        
                    ],
                ]
            ))
            ->add(
                'file',
                VichImageType::class,
                options: [
                ]
            )
            ->setMethod('POST')
            ->addEventListener(FormEvents::PRE_SET_DATA, static function (FormEvent\PreSetDataEvent $event): void {
                //$event->getData()?->setFilepath('enough');
            })
            ->addEventListener(FormEvents::POST_SET_DATA, static function (FormEvent\PostSetDataEvent $event): void {
            })
            //->addViewTransformer($vt)
            //->addModelTransformer($mt)
			*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Image::class,
            'attr' => [
                'id' => $this->getBlockPrefix(),
            ],
            'form_attr' => true,
        ]);
    }
}
