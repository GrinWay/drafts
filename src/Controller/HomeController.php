<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Process\Process;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\UX\Turbo\TurboBundle;

#[Route('', name: 'app_')]
class HomeController extends AbstractController
{
    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator,
    )
    {
    }

    /**
     * @param $appDatabaseUrl
     * @param Request $request
     * @return Response
     */
    #[Route('/', name: 'home')]
    public function home(
        #[Autowire('%env(APP_DATABASE_URL)%')] $appDatabaseUrl,
        Request                                $request,
    ): Response
    {

        return $this->render('home/index.html.twig', [
        ]);
    }

    /**
     * @return Response
     */
    #[Route('/turbo/stream/test', name: 'mercure_test')]
    public function mercureTest(
        Request      $request,
        HubInterface $hub,
    ): Response
    {
        \dump($request->getPreferredFormat());
        if (true || TurboBundle::STREAM_FORMAT === $request->getPreferredFormat()) {
            $turboStreamView = $this->renderView($template = 'home/test.stream.html.twig');
            $hub->publish(new Update(
                ['test'],
                $turboStreamView,
            ));
        }

        return $this->redirectToRoute('app_home', [

        ]);
    }
}
