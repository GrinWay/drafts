<?php

namespace App\Controller;

use Ajaxray\PHPWatermark\Watermark;
use Gregwar\Image\Adapter\Adapter;
use Gregwar\Image\Adapter\GD;
use Gregwar\Image\Image;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\HttpOptions;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Mime\Part\Multipart\FormDataPart;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\UX\Turbo\TurboBundle;

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

            while(true) {
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

    /**
     * ### HOME ###
     * @param Request $request
     * @param $projectDir
     * @return Response
     */
    #[Route('/', name: 'app_home')]
    public function home(
        Request             $request,
        string              $projectDir,
        HttpClientInterface $client,
    ): Response
    {
        $clientResponse = $client->request('GET', 'https://windows.php.net/downloads/releases/php-8.4.2-nts-Win32-vs17-x64.zip', [
//            'user_data' => 'some data from user',
        ]);

//        foreach($client->stream($clientResponse) as $r => $chunk) {
//        }

        $template = 'home/index.html.twig';
        $parameters = [
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
