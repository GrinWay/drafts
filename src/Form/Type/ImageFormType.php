<?php

namespace App\Form\Type;

use function Symfony\component\string\u;

use Symfony\Component\Form\ChoiceList\ChoiceList;
use App\Doctrine\DTO\UserDto;
use App\Repository\AvatarRepository;
use Symfony\Component\Validator\Constraints;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Form\FormEvents;
use App\Entity\Media\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
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
	) {
		parent::__construct(
			pa: $pa,
		);
	}
	
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
		$getter = static function($o) {
			\dump('getter');
			\dd($o);
			return $v;
		};
		$setter = static function($o, $v) {
			\dump('setter');
			\dd($o);
			return $v;
		};
		
        $builder
            ->add('id',// FormType\FileType::class,
				options: [
					'disabled' => true,
				],
			)
            ->add('fileDimensions', FormType\ChoiceType::class, //OnlyCountryType::class,
				options: [
					'label' => 'CHOICE TYPE',
					//'mapped' => false,
					'choices'  => [
						'LAND' => 'landspace',
						'PORT' => 'portrait',
						'CUBE' => 'cube',
					],
					'getter' => $getter,
					'setter' => $setter,
					//'only' => ['ru', 'us', 'jp'],
					
					//'choice_loader' => $this->avatarRepo->findAll(...),
					//'choices'  => \App\Type\Media\MediaType::TYPES,
					//'choice_value' => static fn($v): string => $v?->id ?: '',
					//'choice_label' => static fn($v): string => ($name = $v?->name) ? \mb_strtoupper($name) : $name,
					// ChoiceList adds cache
					/*
					'choices'  => [
						'ALEX' => new UserDto(11, 'Alex', 22),
						'AZAK' => new UserDto(22, 'Azak', 21),
						'MARIAM' => new UserDto(33, 'Mariam', 12),
					],
					'choice_attr' => ChoiceList::attr($this, static fn($c, $k, $v): array => $c?->id % 2 ? ['class' => $k.$v.'_text-bg-success'] : ['class' => $k.$v.'_text-bg-primary']),
					
					'choice_attr' => [
						'ALEX' => ['class' => 'ALEX'],
						'AZAK' => ['class' => 'AZAK'],
						'MARIAM' => ['class' => 'MARIAM'],
					],
					'group_by' => static fn($v): string => $v?->id % 2 ? 'Нечётный' : 'Чётный',
					'preferred_choices' => static fn($v): bool => 22 === $v?->id,
					'expanded' => false,
					'multiple' => false,
					*/
					//'placeholder' => null,
					'attr' => [
					],
					'constraints' => [
						/*
						new Constraints\CssColor([
							'hex_long',
							'hex_long_with_alpha',
							'basic_named_colors',
						]),
						*/
					],
				]
			)
            ->add('file', VichImageType::class,
				options: [
				]
			)
			->setMethod('PATCH')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Image::class,
        ]);
    }
}
