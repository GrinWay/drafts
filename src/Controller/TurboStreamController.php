<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\UX\Turbo\TurboBundle;

class TurboStreamController extends AbstractController
{
    #[Route('/turbo/stream/{turboStreamAction?}', name: 'app_turbo_stream')]
    public function index(
		?string $turboStreamAction,
		Request $request,
	): Response {
		$template = 'turbo_stream/index.html.twig';
		$params = [
			'turbo_stream_action' => $turboStreamAction,
		];
		
		if (null === $turboStreamAction) {
			return $this->render($template, $params);
		}
		
		$request->setRequestFormat(TurboBundle::STREAM_FORMAT);
		
        return $this->renderBlock(
			$template,
			'train_block',
			$params,
		);
    }
}
