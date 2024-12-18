<?php

namespace App\Controller;

use App\Entity\Movie;
use Doctrine\ORM\EntityManagerInterface;
use SensioLabs\AnsiConverter\AnsiToHtmlConverter;
use SensioLabs\AnsiConverter\Theme\SolarizedTheme;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Symfony\UX\Turbo\TurboBundle;
use function Zenstruck\Foundry\faker;

class HomeController extends AbstractController
{
    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator,
    )
    {
    }

    /**
     * ### HOME ###
     */
    #[Route('/', name: 'app_home')]
    public function home(
        #[Autowire('%env(APP_DATABASE_URL)%')] $databaseUrl,
        Request                                $request,
        KernelInterface                        $kernel,
        EntityManagerInterface                 $em,
    ): Response
    {
        $template = 'home/index.html.twig';
        $parameters = [
            'databaseUrl' => $databaseUrl,
        ];
        return $this->render($template, $parameters);
    }

    /**
     * @param Request $request
     * @param HubInterface $hub
     * @return Response
     */
    #[Route('/turbo/stream/test', name: 'app_mercure_test')]
    public function mercureTest(
        Request      $request,
        HubInterface $hub,
    ): Response
    {
        $preferredFormat = $request->getPreferredFormat();
        \dump($preferredFormat);

        if (TurboBundle::STREAM_FORMAT === $preferredFormat) {
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
