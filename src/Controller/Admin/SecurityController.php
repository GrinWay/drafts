<?php

namespace App\Controller\Admin;

use function Symfony\component\translation\t;
use function Symfony\Component\String\u;

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
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractDashboardController
{
	public function __construct(
		private readonly TranslatorInterface $t,
		private readonly AdminContextProvider $adminContextProvider,
		private readonly AuthenticationUtils $authenticationUtils,
	) {}
	
	#[Route('/admin/login/{_locale<%app.regex.locale%>?ru}', name: 'app_admin_login')]
    public function index(): Response {
		$error = $this->authenticationUtils->getLastAuthenticationError();
        $lastUsername = $this->authenticationUtils->getLastUsername();
		
        return $this->render('@EasyAdmin/page/login.html.twig', [
			'error' => $error,
            'last_username' => $lastUsername,
			'page_title' => 'Admin login',
			'csrf_token_intention' => 'authenticate',
		]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new();
    }

    public function configureMenuItems(): iterable
    {
		return [];
    }
	
	public function configureUserMenu(UserInterface $user): UserMenu
    {
		return UserMenu::new();
	}
}
