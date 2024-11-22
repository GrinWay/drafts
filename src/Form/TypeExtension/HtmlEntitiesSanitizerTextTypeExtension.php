<?php

namespace App\Form\TypeExtension;

use Symfony\Component\HtmlSanitizer\HtmlSanitizerInterface;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use App\EventSubscriber\Form\SanitizeHtmlEntitiesSubscriber;
use Symfony\UX\Dropzone\Form\DropzoneType;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type as FormType;
use App\Form\DataTransformer\NormalizeLocaleDataTransformer;

class HtmlEntitiesSanitizerTextTypeExtension extends AbstractTypeExtension
{
	public function __construct(
        private readonly PropertyAccessorInterface $pa,
        private readonly HtmlSanitizerInterface $sanitizer,
	) {}
	
	public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                SanitizeHtmlEntitiesSubscriber::SANITIZE_HTML_ENTITIES => false,
            ])
            ->setAllowedTypes(SanitizeHtmlEntitiesSubscriber::SANITIZE_HTML_ENTITIES, 'bool')
        ;
    }
	
	public function buildForm(FormBuilderInterface $builder, array $options): void
    {
		$builder->addEventSubscriber(new SanitizeHtmlEntitiesSubscriber(
			pa: $this->pa,
			sanitizer: $this->sanitizer,
		));
    }
	
    public static function getExtendedTypes(): iterable
    {
        yield FormType\TextType::class;
    }
}
