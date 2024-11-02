<?php

namespace App\Controller\Admin;

use function Symfony\component\translation\t;

use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Dto\BatchActionDto;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Security\Permission;
use Symfony\Component\ExpressionLanguage\Expression;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use Symfony\Component\Form\Extension\Core\Type as FormType;
use App\Form\Field\Filter\HandyDateFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ComparisonFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use App\Form\Field\PrefixedAvatarField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AvatarField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use App\Form\Field\DateIntervalField;
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
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field as EaField;
use EasyCorp\Bundle\EasyAdminBundle\Provider\AdminContextProvider;

class AppEasyAdminCommentCrudController extends AbstractCrudController
{
	public function __construct(
		// ERR
		//private readonly AdminContext $adminContext,
		private readonly AdminContextProvider $adminContextProvider,
		#[Autowire('%app.public_img_dir%')]
		private readonly string $publicImgDir,
	) {}
	
    public static function getEntityFqcn(): string
    {
        return AppEasyAdminComment::class;
    }
	
	public function configureCrud(Crud $crud): Crud
    {
        return $crud
			->setEntityPermission(new Expression('auth_checker.isGranted("PUBLIC_ACCESS")'))
			//->setEntityPermission(Permission::EA_ACCESS_ENTITY)
			
			->setEntityLabelInSingular(static fn($entity, $pageName) => 'Комментарий') // try callback (?Entity, ?pageName)
			->setEntityLabelInPlural(static fn($entity, $pageName) => 'Комментарии') // try callback (?Entity, ?pageName)
			->setPaginatorUseOutputWalkers(true)
			->setPaginatorFetchJoinCollection(true)
			->overrideTemplates([
				//'label/null' => 'admin/crud/comment/field/null.html.twig',
			])
			->addFormTheme('admin/crud/theme/field/text.html.twig')
		/*
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
	
	public function configureActions(Actions $actions): Actions
	{
		return $actions
			->add(Crud::PAGE_INDEX, Action::new('testCalc', null, 'fa fa-home')
				->linkToCrudAction('testCalcMethod')
				->createAsGlobalAction()
			)
			/*
			*/
			->addBatchAction(Action::new('app.approve_users', 'Approve Users')
				->linkToCrudAction('approveUsers')
                ->addCssClass('btn btn-primary')
                ->setIcon('fa fa-user-check')
			)
		;
	}
	
	/**
     * Batch Action
     */
	public function testCalcMethod(AdminContext $adminContext) {
		//\dump(\get_debug_type($adminContext));
		return $this->render('admin/test/test.html.twig');
	}
	
	/**
     * Batch Action
     */
	public function approveUsers(?BatchActionDto $batchActionDto) {
		$defaultUrl = $this->container->get(AdminUrlGenerator::class)
			->unsetAll()
			->setDashboard(DashboardController::class)
			->generateUrl()
		;
		if (null !== $batchActionDto) {
			$entityFqcn = $batchActionDto->getEntityFqcn();
			$ids = $batchActionDto->getEntityIds();
			/*
			\dump(
				\sprintf('Batch Action for %s: (%s)', $entityFqcn, \implode(', ', $ids)),
			);
			*/
		}
		
		return $this->redirect($batchActionDto?->getReferrerUrl() ?? $defaultUrl);
	}
	
	public function configureFilters(Filters $filters): Filters
    {
        return $filters
			//->add(ComparisonFilter::new('id'))
            /*
			->add(ComparisonFilter::new('authorName')
				->setFormTypeOption('comparison_type', FormType\TextType::class)
				->setFormTypeOption('value_type', FormType\TextType::class)
			)
			*/
            ->add(ChoiceFilter::new('type')->setChoices(CommentType::ALL)->canSelectMultiple())
			->add(HandyDateFilter::new('updatedAt'))
        ;
    }

	protected function getRedirectResponseAfterSave(AdminContext $context, string $action): RedirectResponse
	{
		if (Action::NEW === $action) {
			//return $this->redirectToRoute('app_home_home');
		}
		
		return parent::getRedirectResponseAfterSave($context, $action);
	}

    public function configureFields(string $pageName): iterable
    {
		//yield FormField::addTab('Main');
		//yield FormField::addColumn(6);
		//yield FormField::addFieldset('Набор полей');
		yield IdField::new('id')
			->hideOnForm()
		;
        yield TextField::new('authorName', t('authorName', domain: 'app.admin+intl-icu'))
			->setSortable(false)
			->setColumns('col-sm-6 col-md-3')
			->setTemplatePath('admin/crud/comment/field/author_name.html.twig')
		;
		yield NumberField::new('numberFromZeroToHundred')
			->setColumns('col-sm-6 col-md-3')
		;
		yield AvatarField::new('avatar')
			->setHeight(50)
		;
		yield SlugField::new('slug')
			->setTargetFieldName(['authorName', 'type', 'avatar'])
			->setHelp('Это поле устанавливается автоматически, для цели поиска в поисковой строке')
		;
		yield FormField::addRow();
		yield TextEditorField::new('text')
			->formatValue(static fn($text, $e) => $text)
			->setFormTypeOptions([
				//'block_name' => 'my_block_name_text_editor',
			])
		;
		
		/*
		yield FormField::addTab(
			//t('secondary_inf', domain: 'app.admin+intl-icu')
			//icon: 'home',
		)
			->setIcon('home')
			//->setCssClass('my-class')
			->setHelp('Оставшаяся часть')
		;
		*/
		//yield FormField::addColumn(6);
		//yield FormField::addFieldset('collapsible');
		yield DateIntervalField::new('invitationInterval')
			->setSortable(false)
		;
		yield EaField\DateTimeField::new('updatedAt')->hideOnForm();			
		yield EaField\ChoiceField::new('type')
			->allowMultipleChoices(false)
			->autocomplete()
			->setChoices(CommentType::ALL)
			
			->onlyOnForms()
		;	
		yield TextField::new('type')
			->setTextAlign('center')
			->hideOnForm()
		;
    }
	
	public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
		$qb = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);
		
		/*
		\dump(
			'getSort',
			$searchDto->getSort(),
			'count getSort',
			\count($searchDto->getSort()),
			'isSortingField',
			$searchDto->isSortingField('id'),
			'getSortDirection',
			$searchDto->getSortDirection('id'),
			'getQuery',
			$searchDto->getQuery(),
			'getQueryTerms',
			$searchDto->getQueryTerms(),
			'getSearchableProperties',
			$searchDto->getSearchableProperties(),
			'getAppliedFilters',
			$searchDto->getAppliedFilters(),
			'getSearchMode',
			$searchDto->getSearchMode(),
		);
		*/
		
		return $qb;
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
		
		return $responseParameters;
	}
	
	public function configureAssets(Assets $assets): Assets
    {
		return $assets
			
		;
	}
}
