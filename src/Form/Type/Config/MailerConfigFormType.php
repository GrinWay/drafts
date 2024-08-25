<?php

namespace App\Form\Type\Config;

use function Symfony\component\string\u;

use Symfony\Component\Form\FormEvents;
use App\Form\Type\AbstractFormType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use App\Doctrine\DTO\UserDto;
use App\Repository\AvatarRepository;
use Symfony\Component\Validator\Constraints;
use Vich\UploaderBundle\Form\Type\VichImageType;
use App\Entity\Media\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type as FormType;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Form\CallbackTransformer;
use App\Form\DataMapper\CustomConfigParametersDataMapper;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\FormInterface;

class MailerConfigFormType extends AbstractFormType
{
    public function __construct(
		PropertyAccessorInterface $pa,
		private readonly CustomConfigParametersDataMapper $customConfigParametersDataMapper,
		private readonly ParameterBagInterface $parameterBag,
	) {
		parent::__construct(
			pa: $pa,
		);
	}
	
	public function buildForm(FormBuilderInterface $builder, array $options): void
    {	
		$onlyCountries = $this->pa->getValue($options, '[only]');
		
		$builder
			->add($builder->create('APP_MAILER_HEADER_FROM_TITLE', FormType\TextType::class,
				options: [
					'label' => 'От кого',
					'constraints' => [
						//new Constraints\NotBlank(),
					],
					'help' => 'Лучше используй только буквы и цифры',
				],
			)->addEventListener(FormEvents::PRE_SUBMIT, function ($event): void {
				$value = $event->getData();
				$value = \preg_replace('~[\(\)\{\}\[\]\<\>@]~i', '', $value);
				$event->setData($value);
			})
			)
			->add('APP_MAILER_HEADER_SUBJECT', FormType\TextType::class,
				options: [
					'label' => 'Тема',
					'constraints' => [
						new Constraints\NotBlank(),
					],
				],
			)
			->add('default', FormType\CheckboxType::class,
				options: [
					'data' => false,
					'label' => 'Использовать данные по умолчанию',
				],
			)
			->setMethod('PATCH')
		;
		
		$builder->setDataMapper($this->customConfigParametersDataMapper->setRequired($this->pa, $this->parameterBag));
	}
	
    public function configureOptions(OptionsResolver $resolver): void
    {
		$resolver
			->setDefaults([
				'attr' => [
					'novalidate' => 'novalidate',
				],
			])
		;
    }
}
