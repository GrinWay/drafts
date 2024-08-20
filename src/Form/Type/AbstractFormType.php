<?php

namespace App\Form\Type;

use function Symfony\Component\String\u;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Translation\TranslatableMessage;
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
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractFormType extends AbstractType
{
	public function __construct(
        protected readonly PropertyAccessorInterface $pa,
	) {}

	/**
	* @return void
	*/
	public function configureOptions(OptionsResolver $resolver)
    {
		$resolver
			->setDefaults([
				//'translation_domain' => 'app.form',
				'choice_translation_domain' => 'app.form',
				'label_format' => '%name%',
				//'label_format' => t('%name%', [], 'app.form'),
			])
		;
    }
	
	/**
	* @return void
	*/
	public function buildView(FormView $view, FormInterface $form, array $options)
    {
		$this->customizeStyle1(
			$view,
			$options,
		);
    }
	
	private function customizeStyle1(FormView $view, array $options): void {
		if (true === $this->pa->getValue($options, '[style1]')) {
			$view->vars['style1'] = true;
		}
	}
	
	/**
	* @return string
	*/
	public function getBlockPrefix(): string
    {
		$resultBlockPrefix = 'app_'.parent::getBlockPrefix();
        //\dd($resultBlockPrefix);
        return $resultBlockPrefix;
    }
}
