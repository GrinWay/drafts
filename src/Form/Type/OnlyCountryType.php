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
use Symfony\Component\Form\CallbackTransformer;

class OnlyCountryType extends AbstractFormType
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
		$pa = $this->pa;
		
		$vtf = static function($v) {
			\dump('vtf');
			return $v;
		};
		$vto = static function($v) {
			\dump('vto');
			return $v;
		};
		$vt = new CallbackTransformer($vtf, $vto);
		
		$mtf = static function($v) {
			\dump('mtf');
			return $v;
		};
		$mto = static function($v) {
			\dump('mto');
			return $v;
		};
		$mt = new CallbackTransformer($mtf, $mto);
		
		$builder
			->add($builder->create('country', FormType\CountryType::class,
				options: [
					'choice_filter' => static function($v) use (&$options, $pa): bool {
						$onlyCountries = $pa->getValue($options, '[only]');
						if (null === $onlyCountries) {
							return true;
						} else {
							return u($v)->ignoreCase()->containsAny($onlyCountries);
						}
					},
				])
				//->addViewTransformer($vt)
				//->addModelTransformer($mt)
			)
		;
	}
	
    public function configureOptions(OptionsResolver $resolver): void
    {
		$resolver
			->setDefaults([
				'only' => null,
				'inherit_data' => true,
			])
			->setAllowedTypes('only', ['null', 'array'])
		;
    }
}
