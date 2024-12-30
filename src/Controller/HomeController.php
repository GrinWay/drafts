<?php

namespace App\Controller;

use App\Entity\Todo;
use Doctrine\ORM\EntityManagerInterface;
use ElGigi\CommonMarkEmoji\EmojiExtension;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Knp\Component\Pager\PaginatorInterface;
use League\CommonMark\CommonMarkConverter;
use League\HTMLToMarkdown\HtmlConverter;
use parallel\Channel;
use parallel\Runtime;
use Parsedown;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\UX\Turbo\TurboBundle;
use function Symfony\Component\Translation\t;

class HomeController extends AbstractController
{
    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator,
    )
    {
    }

    #[Route('/event-source', 'app_event_source')]
    public function eventSource()
    {
        return new StreamedResponse(static function (): void {
            \ob_end_clean();
            $idx = 0;

            while (true) {
                echo \sprintf('event: mess%1$sdata: random data%2$s%1$sid: %3$s%1$s%1$s', \PHP_EOL, \random_int(0, 100), ++$idx);
                \flush();
                \sleep(1);
            }
        }, headers: [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
        ]);
    }

    #[Route('/custom-pagination', name: 'app_custom_paginator')]
    public function customPagination(
        PaginatorInterface $paginator,
                           $projectDir,
        Request            $request,
    )
    {
        $pagination = $paginator->paginate($projectDir, $request->query->getInt('page', 1), 10);

        $template = 'home/custom_pagination.html.twig';
        $parameters = [
            'pagination' => $pagination,
        ];
        return $this->render($template, $parameters);
    }

    /**
     * ### HOME ###
     * @param Request $request
     * @param $projectDir
     * @return Response
     */
    #[Route('/', name: 'app_home')]
    public function home(
        Request                       $request,
        string                        $projectDir,
        PaginatorInterface            $paginator,
        EntityManagerInterface        $em,
        #[Autowire('%env(APP_URL)%')] $appUrl,
        TranslatorInterface           $trans,
    ): Response
    {
        $a = [
            'v',
            1,
            1.,
        ];

        $converter = new CommonMarkConverter([
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);
        $converter->getEnvironment()->addExtension(new EmojiExtension());
        $html = $converter->convert($trans->trans('test_parsedown'));

        $direction = $request->query->get('direction', $defaultDirection = 'DESC');
        $direction = \strtoupper($direction);
        if ('ASC' !== $direction && 'DESC' !== $direction) {
            $direction = $defaultDirection;
        }

        $orderBy = $request->query->get('sort', $defaultOrderBy = 'o.id');
        if (!\str_starts_with($orderBy, 'o.')) {
            $orderBy = $defaultOrderBy;
        }

        $dql = \sprintf('SELECT o FROM %s o ORDER BY %s %s', Todo::class, $orderBy, $direction);
        $query = $em->createQuery($dql);

        $pagination = $paginator->paginate(
            $query,
            $request->query->get('page', 1),
            5,
        );

        $pagination->setParam('direction', $direction);
        $pagination->setParam('sort', $orderBy);
        $pagination->setCustomParameters([
            'align' => 'left',
            'size' => 'small',
        ]);

        $pagination->setPageRange(10);

//        $pagination->setUsedRoute('app_custom_paginator');

//        \dump($pagination->getPaginatorOptions());

        $template = 'home/index.html.twig';
        $parameters = [
            'pagination' => $pagination,
            'html' => $html,
            'path' => $projectDir.'/.env',
        ];
        $response = $this->render($template, $parameters);
        return $response;
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

    public function noRouteMethod()
    {
        return $this->render('home/test_escaping_strategy_as_first_ext.js.twig');
    }
}
