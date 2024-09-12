<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Type\Note\NoteType;

/**
 * @codeCoverageIgnore
 */
#[Route('/session')]
class SessionController extends AbstractController
{
    #[Route('/clear', name: 'app_session_clear')]
    public function clear(
        Request $request,
    ): Response {
        $request->getSession()->clear();
        //$this->addFlash(NoteType::NOTICE, 'Session была очищена.');
        return $this->redirectToRoute('app_home_home');
    }
}
