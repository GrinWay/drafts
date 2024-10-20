<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use Symfony\Component\HttpFoundation\RedirectResponse;
use EasyCorp\Bundle\EasyAdminBundle\Config\Option\SearchMode;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use App\Type\Comment\CommentType;
use App\Entity\AppEasyAdminComment;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field as EaField;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;

class AppEasyAdminCommentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return AppEasyAdminComment::class;
    }
	
	public function configureCrud(Crud $crud): Crud
    {
        return $crud
			->setEntityLabelInSingular(static fn($entity, $pageName) => 'Комментарий') // try callback (?Entity, ?pageName)
			->setEntityLabelInPlural(static fn($entity, $pageName) => 'Комментарии') // try callback (?Entity, ?pageName)
		/*
			->setDateIntervalFormat('%%y Year(s) %%m Month(s) %%d Day(s)')
			->setTimezone('UTC')
			->renderContentMaximized()
			->renderSidebarMinimized()
			//   %entity_name%, %entity_as_string%,
			//   %entity_id%, %entity_short_id%
			//   %entity_label_singular%, %entity_label_plural%
			->setPageTitle(Crud::PAGE_INDEX, '%entity_label_plural%') // try closure
			->setHelp(Crud::PAGE_EDIT, '...')
			->setNumberFormat('%.2d')
			->setSearchFields(['authorName', 'text', 'type'])
			->setAutofocusSearch()
			->setDefaultSort(['id' => 'DESC'])
			->setSearchMode(SearchMode::ANY_TERMS)
			->setThousandsSeparator(''')
			->setDecimalSeparator('.')
			->setPaginatorUseOutputWalkers(true)
			->setPaginatorFetchJoinCollection(true)
			->overrideTemplate('label/null', 'admin/fields/my_id.html.twig')
			->addFormTheme('foo.html.twig')
			->setFormThemes(['my_theme.html.twig', 'admin.html.twig'])
			->setFormOptions([
				'validation_groups' => ['Default', 'my_validation_group']
			])
			->setFormOptions(
				['validation_groups' => ['my_validation_group']], // new
				['validation_groups' => ['Default'], '...' => '...'], // edit
			)
		*/
		;
	}

	protected function getRedirectResponseAfterSave(AdminContext $context, string $action): RedirectResponse
	{
		return parent::getRedirectResponseAfterSave($context, $action);
	}

    public function configureFields(string $pageName): iterable
    {
		if (Crud::PAGE_INDEX === $pageName || Crud::PAGE_DETAIL === $pageName) {
			yield IdField::new('id');
		}
        yield TextField::new('authorName');
		yield TextEditorField::new('text');
		if (Crud::PAGE_INDEX === $pageName || Crud::PAGE_DETAIL === $pageName) {
			yield EaField\DateTimeField::new('updatedAt');			
		}
		if (Crud::PAGE_NEW === $pageName || Crud::PAGE_EDIT === $pageName) {
			yield EaField\ChoiceField::new('type')
				->allowMultipleChoices(false)
				->autocomplete()
				->setChoices(CommentType::ALL)
			;	
		} else {
			yield TextField::new('type');
		}
    }
	/*
	*/
	
	public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
		return parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);
	}

	public function createEntity(string $entityFqcn)
    {
		$entityClass = $this->getEntityFqcn();
		return new $entityClass();
	}
	
	public function configureResponseParameters(KeyValueStore $responseParameters): KeyValueStore
    {
		if (Crud::PAGE_INDEX === $responseParameters->get('pageName')) {
			$responseParameters->setIfNotSet('app_custom_value_added_in_method', 'value');
		}
		
		return $responseParameters
			
		;
	}
}
