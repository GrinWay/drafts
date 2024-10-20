<?php

namespace App\Controller\Admin;

use function Symfony\component\translation\t;
use function Symfony\Component\String\u;

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
use Symfony\Component\Routing\Attribute\Route;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Config\Option;
use Symfony\Contracts\Translation\TranslatorInterface;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;

class DashboardController extends AbstractDashboardController
{
	//AdminContext $adminContext,
	
	public function __construct(
		private readonly TranslatorInterface $t,
		private readonly AdminContextProvider $adminContextProvider,
	) {}
	
	#[Route('/admin/{_locale<%app.regex.locale%>?ru}', name: 'app_admin_index')]
    public function index(): Response {
		$adminUriGenerator = $this->container->get(AdminUrlGenerator::class);
		
		\dump(
			\get_debug_type($this->adminContextProvider->getContext()),
			//\get_debug_type($adminContext),
		);
		
		$uri = $adminUriGenerator->setController(Controller\AppEasyAdminCinemaController::class)->generateUrl();
		//return $this->redirect($uri);
		
        //return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        return $this->render('admin/index/index.html.twig', []);
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
		yield MenuItem::section('MAIN');
		yield MenuItem::linkToDashboard('dashboard', null)
			//->setCssClass('badge p-2 pb-3 text-bg-dark')
			//->setLinkRel('alternate')
			//->setLinkTarget('_blank')
			//->setPermission('VOTE_IS_AUTH')
			->setBadge('New', 'success', [
			])
		;
		yield MenuItem::linkToRoute('login', null, 'app_admin_login');
        
		yield MenuItem::section();
        //yield MenuItem::linkToCrud('Item', 'fa fa-tags', Entity\AppEasyAdminCinema::class);
        //yield MenuItem::linkToCrud('Item', 'fa fa-tags', Controller\AppEasyAdminCinemaController::class);
		
		yield MenuItem::section('website_map');
		yield MenuItem::linkToRoute('home', 'fa fa-home', 'app_home_home', [
			'passedOtpNumber' => 1,
		]);
		yield MenuItem::subMenu('external_links', null)->setSubItems([
			MenuItem::linkToUrl('Google', null, 'https://www.google.com/'),
			MenuItem::linkToUrl('GitHub', null, 'https://github.com/'),
			MenuItem::linkToUrl('vk', null, 'https://vk.com/'),
		]);
		
		$appEasyAdminCommentCrudUrl = $this->container->get(AdminUrlGenerator::class)
			->unsetAll()
			->setController(AppEasyAdminCommentCrudController::class)
			->setAction(Crud::PAGE_EDIT)
			->set(EA::ENTITY_ID, 1)
			->generateUrl()
		;
        yield MenuItem::subMenu('Edit', null)->setSubItems([
			/**
			 * Crud::PAGE_INDEX
			 * Crud::PAGE_DETAIL
			 * Crud::PAGE_EDIT
			 * Crud::PAGE_NEW
			 */
			//yield MenuItem::linkToCrud('Comment', null, Entity\AppEasyAdminComment::class),
			//yield MenuItem::linkToUrl('Comment', null, $appEasyAdminCommentCrudUrl),
		]);
		
		yield MenuItem::section();
		
		yield MenuItem::linkToLogout('logout', null);
		yield MenuItem::linkToExitImpersonation('stop_impersonation', null);
    }
	
	public function configureUserMenu(UserInterface $user): UserMenu
    {
		//return UserMenu::new();
		
		\dump($user);
		
		return parent::configureUserMenu($user)
			->displayUserName(true)
			//->setName($user->getPassport()->getFirstName())
			//->setAvatarUrl('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT1EH58c8MFODbyIixSDhzUtohot5NU7-I0mw&s')
			->addMenuItems([
				MenuItem::linkToUrl('GitHub', null, 'https://github.com/'),
				MenuItem::linkToUrl('Google', null, 'https://google.com/'),
			])
		;
	}
	
	public function configureCrud(): Crud
    {
        return Crud::new()
			->setPaginatorPageSize(50)
			->setPaginatorRangeSize(10)
			->hideNullValues(false)
			->overrideTemplate('crud/field/id', 'admin/component/field/id.html.twig')
			->setDateTimeFormat('full', 'short')
			->setEntityPermission('PUBLIC_ACCESS')
		;
	}
}
