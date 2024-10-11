<?php

namespace App\Controller;

use App\Type\Note\NoteType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\Turbo\TurboBundle;

class PostController extends AbstractController
{
    #[Route('/post', name: 'app_post')]
    public function index(
		Request $request,
	): Response {
		
		if (TurboBundle::STREAM_FORMAT === $request->getPreferredFormat()) {
			$this->addFlash(NoteType::NOTICE, 'Список раскрыт');
			$request->setRequestFormat(TurboBundle::STREAM_FORMAT);
			return $this->render('post/Post.stream.html.twig');
		}
		
        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
        ]);
    }
}
