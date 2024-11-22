<?php

namespace App\Form\Type;

use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Doctrine\ORM;
use Symfony\Bundle\SecurityBundle\Security;
use App\Entity\Media\Media;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;
use Symfony\UX\Autocomplete\Form\BaseEntityAutocompleteType;

#[AsEntityAutocompleteField(route: 'ux_autocomplete_admin_prefixed')]
class MediaAutocompleteField extends AbstractType
{
	public function __construct(
		private readonly PropertyAccessorInterface $pa,
	) {}
	
    public function configureOptions(OptionsResolver $resolver): void
    {
		$pa = $this->pa;
		
        $resolver->setDefaults([
            'class' => Media::class,
            'multiple' => true,
			'choice_label' => static function ($model, string $key, mixed $value) {
				return \sprintf('<span class="font-monospace"><small>[%s]</small> %s %s</span>', \get_debug_type($model), $value, $model->getFilepath());
			},
			'tom_select_options' => [
				'create' => true,
				'placeholder' => 'Спасибо Tom-Select',
				'hideSelected' => false,
				'loadThrottle' => 500,
				'maxOptions' => 5,
				'allowEmptyOption' => false,
				'closeAfterSelect' => false,
				'highlight' => false,
				'duplicates' => true,
				'addPrecedence' => true,
				'preload' => true,
				'hidePlaceholder' => true,
				'createOnBlur' => true,
				'createFilter' => '^3.*',
				'delimiter' => "\0",
			],
			//'min_characters' => 1,
			'query_builder' => static function (Options $options) use ($pa) {
				return static function (ORM\EntityRepository $er) use ($options, $pa) {
					$excludedIds = $pa->getValue($options, '[extra_options][excluded_ids]');
					
					$qb = $er->createQueryBuilder('entity');
					
					if (empty($excludedIds)) {
						return $qb;
					}
					
					return $qb
						->andWhere('entity.id NOT IN (:excludedIds)')
						->setParameter('excludedIds', \implode(', ', $excludedIds))
					;
				};
			},
			'filter_query' => static function(ORM\QueryBuilder $qb, string $query, ORM\EntityRepository $repository) {
				if (empty($query)) {
					return;
				}
				
				$qb
					->andWhere("entity.filepath LIKE :query")
					->setParameter('query', \sprintf('%%%s%%', $query))
				;
			},
			/*
			*/
            //'security' => 'PUBLIC_ACCESS',
            'security' => static function (Security $security): bool {
				return $security->isGranted('PUBLIC_ACCESS');
			},
        ]);
    }

    public function getParent(): string
    {
        return BaseEntityAutocompleteType::class;
    }
}
