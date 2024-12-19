<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DynamicController extends AbstractController
{
    public function __invoke(
        ?string $contentDocument,
    ): Response
    {
        return $this->render('dynamic/index.html.twig', [
            'content' => $contentDocument,
        ]);
    }
}
