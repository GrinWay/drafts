<?php

namespace App\Form\Type;

use function Symfony\Component\String\u;

use App\Repository\MediaRepository;
use App\Entity\Media;
use App\EventSubscriber\Form\ForbidModifyPropEventSubscriber;
use App\EventSubscriber\Form\ForbidModifyPropEventSubscriberChildHook;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type as FormType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;
use App\Entity\Task;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use App\Contract\Form\PreventModifyingPropsOfEntity;
use App\Entity\TaskEntity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class TaskFormType extends AbstractFormType
{
    public function __construct(
        PropertyAccessorInterface $pa,
        private readonly EntityManagerInterface $em,
        private readonly RequestStack $requestStack,
        private readonly UrlGeneratorInterface $urlGenerator,
        private $enUtcCarbon,
    ) {
		parent::__construct(
			pa: $pa,
		);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
		$mTF = static function($v) {
			\dump('(MODEL trans) to form');
			return $v;
		};
		$mTO = static function($v) {
			\dump('(MODEL trans) to obj');
			$v->setName('CHANGED IN MODEL TRANS');
			return $v;
		};
		$vTO = static function($v) {
			\dump('(VIEW trans) to obj');
			$v->setName('CHANGED IN VIEW TRANS');
			return $v;
		};
		$vTF = static function($v) {
			\dump('(VIEW trans) to form');
			return $v;
		};
		
		// TODO: PreventModifyingPropsOfEntity (usage)
		$forbidModifyPropsChildHook = new ForbidModifyPropEventSubscriberChildHook(
			$this->pa,
			$preventModifyingPropsOfEntity = new PreventModifyingPropsOfEntity('id'),
		);
		
        $builder
			//->add('id')
            ->add($builder->create(
                'name', //FormType\DateTimeType::class,
                options: [
					'empty_data' => null,
					//'inherit_data' => true,
                    'label' => 'Name',
					'attr' => [
						'placeholder' => 'Name',
					],
					'row_attr' => [
						'class' => 'form-floating',
					],
					'label_attr' => [
						//'class' => 'my-class',
					],
					'constraints' => [
					],
                    //'label' => $this->pa->getValue($options, "[form_type_nested?][name?][label?]"),
                    'required' => $this->pa->getValue($options, "[form_type_nested?][name?][required?]"),
					//'getter' => static fn($obj, $form) => \strtolower($obj->getName()),
					//'setter' => static fn(&$obj, $value, $form) => $obj->setName(\strtoupper($value)),
                ])
				//->addEventSubscriber($forbidModifyPropsChildHook)
			)
            ->add('deadLine', YearMonthDayHourMinuteSecondType::class, //DateTimeAsTextType::class,
				options: [
					'label' => $this->pa->getValue($options, "[form_type_nested?][dead_line?][label?]"),
					'required' => $this->pa->getValue($options, "[form_type_nested?][dead_line?][required?]"),
					'carbon_class' => \Carbon\Carbon::class,
					//'block_name' => 'dead_line',
					//'inherit_data' => true,
				]
			)
            ->add(
                'agree',
                FormType\CheckboxType::class,
                options: [
                    'mapped' => false,
					'label_attr' => [
						'class' => 'checkbox-switch',
					],
                ],
            )
            ->add($builder->create('topic', EntityAsIdTextType::class,
				options: [])
				//->addEventListener(FormEvents::PRE_SUBMIT, static function($e) {})
			)
            ->add('unmappedChoice', FormType\ChoiceType::class,
				options: [
					'mapped' => false,
					'block_name' => 'unmapped_choice',
					'display_count' => true,
					'selected_value' => 'two',
					'choices' => [
						'ONE' => 'one',
						'TWO' => 'two',
					],
					'attr' => [
						'class' => 'form-control',
					],
				],
			)
			//->addViewTransformer(new CallbackTransformer($vTF, $vTO))
            //->setMethod('POST')
            //->setAction($this->urlGenerator->generate($this->requestStack->getCurrentRequest()->attributes->get('_route', $this->requestStack->getCurrentRequest()->attributes->get('_route_params'))))
		;
		
		// TODO: PreventModifyingPropsOfEntity (usage)
		$forbidModifyProps = new ForbidModifyPropEventSubscriber(
			$this->pa,
			$preventModifyingPropsOfEntity,
		);

		if (true === $options['forbid_modify_props_feature']) {
			$builder
				->addEventSubscriber($forbidModifyProps)
			;
		}
    }

    public function configureOptions(OptionsResolver $taskType)
    {
        $nested = 'form_type_nested';
        $nestedDefaults = [
            'label' => null,
            'required' => true,
        ];

        $taskType
            ->setDefaults([
				'help' => 'Task form',
				'data_class' => Task::class,
				'allow_extra_fields' => true,
				'forbid_modify_props_feature' => true,
                //'csrf_field_name' => '_token',
            ])
            ->setDefault('form_type_nested', static function (
                OptionsResolver $nested,
                Options $_,
            ) use (&$nestedDefaults): void {
                $nested
                    ->setPrototype(true)
                    ->setDefaults($nestedDefaults)
                    ->setAllowedTypes('label', ['string', 'null'])
                    ->setAllowedTypes('required', ['bool'])
                ;
            })
            ->setNormalizer($nested, function (Options $options, $value) use ($nested, &$nestedDefaults) {
                $originPropNames = \array_map(static fn($el) => (string) u($el->getName())->snake(), (new \ReflectionClass(Task::class))->getProperties());
                $this->passedPropNames = \array_keys($value);
                if ([] !== ($diff = \array_diff($this->passedPropNames, $originPropNames))) {
                    throw new \InvalidArgumentException(\sprintf(
                        'Allowed values for [%s]: %s. Remove key(s): "%s"',
                        $nested,
                        \implode(', ', $originPropNames),
                        \implode(', ', $diff),
                    ));
                }
                $defaults = \array_map(static fn($el) => $nestedDefaults, \array_flip($originPropNames));
                return $value + $defaults;
            })
        ;
    }
}
