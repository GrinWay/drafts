<?php

namespace App\Controller;

use App\Form\Type as FormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Media\Media;
use App\Entity\Media\Image;

#[Route('/live/twig/component', name: 'app_live_twig_component_')]
class LiveTwigComponentController extends AbstractController
{
    #[Route('/{id<[0-9]+>?10}', name: 'index')]
    public function index(
		Media $image,
	): Response {
		
		if (!$image instanceof Image) {
			$image = new Image();
		}
		
        return $this->render('live_twig_component/index.html.twig', [
			'image' => $image,
		]);
    }
}
