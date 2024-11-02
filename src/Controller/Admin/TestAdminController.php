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
use App\Controller\AbstractController;

class TestAdminController extends AbstractController
{
	#[Route('/admin/test/{_locale<%app.regex.locale%>?ru}', name: 'app_admin_test')]
    public function index(): Response {
        return $this->render('admin/test/test.html.twig', []);
    }
}
