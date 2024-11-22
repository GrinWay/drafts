<?php

namespace App\Controller\Admin;

use function Symfony\component\translation\t;
use function Symfony\Component\String\u;

use Symfony\Component\Notifier\Message\ChatMessage;
use Symfony\Component\Notifier\ChatterInterface;
use Symfony\Component\Notifier\Bridge\Mercure\MercureOptions;
use App\Notification\ChatNotification\ChatterNotification;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\Recipient\NoRecipient;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Asset\Packages;
use Symfony\Component\WebLink\Link;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Carbon\Carbon;
use Symfony\UX\Chartjs\Model\Chart;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\AppEasyAdminComment;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Option\EA;
use App\Controller\Admin\AppEasyAdminCommentCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Provider\AdminContextProvider;
use Symfony\Component\Security\Core\User\UserInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use App\Controller;
use App\Entity;
use EasyCorp\Bundle\EasyAdminBundle\Config\Locale;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Config\Option;
use Symfony\Contracts\Translation\TranslatorInterface;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Dto\BatchActionDto;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class DashboardController extends AbstractDashboardController
{
	public function __construct(
		private readonly MessageBusInterface $bus,
		private readonly TranslatorInterface $t,
		private readonly AdminContextProvider $adminContextProvider,
		private readonly UrlGeneratorInterface $urlGenerator,
		private readonly EntityManagerInterface $em,
		private readonly ChatterInterface $chatter,
		private readonly PropertyAccessorInterface $pa,
		private readonly RequestStack $requestStack,
		private readonly NotifierInterface $notifier,
		private readonly Packages $asset,
		private $faker,
		#[Autowire('%app.public_img_dir%')]
		private readonly string $publicImgDir,
		private $mobileDetect,
		private readonly ChartBuilderInterface $chartBuilder,
	) {
		//\dump($em->getFilters());
	}
	
	#[Route('/admin/{_locale<%app.regex.locale%>?ru}', name: 'app_admin_index')]
    public function index(): Response {
		$request = $this->requestStack->getCurrentRequest();
		$adminUriGenerator = $this->container->get(AdminUrlGenerator::class);
		
		//
		/*
		$publicImgFilename = $this->asset->getUrl(\sprintf('%s/p4.jpg', $this->publicImgDir));
		$this->addLink($request, (new Link('preload', $publicImgFilename))
			->withAttribute('as', 'image')
			->withAttribute('fetchpriority', 'high')
		);
		*/
		
		//$this->bus->dispatch($message);
		
        return $this->render('admin/index/index.html.twig', [
			//'chart' => $chart,
			//'publicImgFilename' => $publicImgFilename,
		]);
    }

    public function configureDashboard(): Dashboard
    {
		$faviconPath = 'build/image/favicon.svg';
		$svg = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 448 512"><path d="M96 128a128 128 0 1 0 256 0A128 128 0 1 0 96 128zm94.5 200.2l18.6 31L175.8 483.1l-36-146.9c-2-8.1-9.8-13.4-17.9-11.3C51.9 342.4 0 405.8 0 481.3c0 17 13.8 30.7 30.7 30.7l131.7 0c0 0 0 0 .1 0l5.5 0 112 0 5.5 0c0 0 0 0 .1 0l131.7 0c17 0 30.7-13.8 30.7-30.7c0-75.5-51.9-138.9-121.9-156.4c-8.1-2-15.9 3.3-17.9 11.3l-36 146.9L238.9 359.2l18.6-31c6.4-10.7-1.3-24.2-13.7-24.2L224 304l-19.7 0c-12.4 0-20.1 13.6-13.7 24.2z"/></svg>';
		
        return Dashboard::new()
            //->setTitle($svg.' Я админ')
            ->setTitle($this->t->trans('index_dashboard.title', domain: 'app.admin+intl-icu'))
			->setFaviconPath($faviconPath)
			->setTranslationDomain('app.admin+intl-icu')
			//->setTextDirection('rtl')
			// ?
			//->renderContentMaximized()
			//->renderSidebarMinimized()
			//->disableDarkMode()
			->setDefaultColorScheme(Option\ColorScheme::DARK)
			->generateRelativeUrls()
			->setLocales([
                Locale::new(locale: 'en', label: 'English', icon: 'fa-solid fa-tenge-sign')
            ])
			/*
			*/
		;
    }

    public function configureMenuItems(): iterable
    {
		yield MenuItem::section(
			'Редактирование сущностей'
		);
		yield MenuItem::linkToCrud('Комментарии', 'bi-battery-charging', AppEasyAdminComment::class);
		yield MenuItem::linkToUrl('Batch action', 'fa fa-list', $this->container->get(AdminUrlGenerator::class)
			->unsetAll()
			->setController(AppEasyAdminCommentCrudController::class)
			->setAction('approveUsers')
			->generateUrl()
		);
		
		yield MenuItem::section(
			'Routes'
		);
		yield MenuItem::linkToUrl('Home', 'fas fa-home', $this->generateUrl('app_home_home'));
		
		yield MenuItem::section(
			'Security'
		);
		yield MenuItem::linkToRoute('login', null, 'app_admin_login');
		yield MenuItem::linkToLogout('logout', null);
		yield MenuItem::linkToExitImpersonation('stop_impersonation', null);
    }
	
	public function configureUserMenu(UserInterface $user): UserMenu
    {
		//return UserMenu::new();
		
		return parent::configureUserMenu($user)
			->displayUserName(false)
			//->setName($user->getPassport()->getFirstName())
			->setAvatarUrl('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT1EH58c8MFODbyIixSDhzUtohot5NU7-I0mw&s')
			->addMenuItems([
			])
		;
	}
	
	public function configureActions(): Actions
	{
		$globalViewToAuthorAction = Action::new('authorGithub', 'Автор')
			->setCssClass('btn bg-transparent')
			->setHtmlAttributes([
				'target' => '_blank',
			])
			->linkToUrl('https://github.com/GrinWay')
			->createAsGlobalAction()
		;
		
		$indexBtnsClass = 'btn d-block m-0 bg-transparent my-1';
		
		return parent::configureActions()
			->update(Crud::PAGE_INDEX, Action::NEW, static fn(Action $action) => $action->setIcon('fa fa-plus')->setLabel(false)->addCssClass('py-1 px-4'))
			->add(Crud::PAGE_INDEX, $globalViewToAuthorAction)
			->add(Crud::PAGE_INDEX, Action::DETAIL)
			->update(Crud::PAGE_INDEX,
				Action::DETAIL,
				static fn($a) => $a
					//->displayAsButton()
					//->linkToCrudAction(Action::EDIT)
					->setCssClass($indexBtnsClass)
			)
			->update(Crud::PAGE_INDEX,
				Action::EDIT,
				static fn($a) => $a
					//->displayAsButton()
					->setCssClass($indexBtnsClass)
			)
			->update(Crud::PAGE_INDEX,
				Action::DELETE,
				static fn($a) => $a
					//->displayAsButton()
//					->linkToCrudAction(Action::DELETE)
					->setCssClass($indexBtnsClass)
			)
			->reorder(Crud::PAGE_INDEX, [
				Action::NEW, 'authorGithub',
				Action::EDIT, Action::DETAIL, Action::DELETE,
			])
		;
	}
	
	public function configureCrud(): Crud
    {
        return Crud::new()
		
			//->setEntityPermission('ALWAYS_FORBIDDEN')
			
			->overrideTemplates([
				'label/null' => 'admin/crud/field/null.html.twig',
				'crud/field/avatar' => 'admin/crud/field/avatar.html.twig',
			])
			->setPaginatorPageSize(50)
			->setPaginatorRangeSize(10)
			->hideNullValues(false)
			->setDateTimeFormat('full', 'short')
			->setTimezone('UTC')
			->renderContentMaximized()
			->setAutofocusSearch()
			->setDefaultSort(['id' => 'DESC'])
			->setThousandsSeparator('\'')
			->setDecimalSeparator('.')
			->showEntityActionsInlined()
		;
	}
	
	public function configureAssets(): Assets
    {
		return parent::configureAssets()
			->addWebpackEncoreEntry('app')
		;
	}
}
