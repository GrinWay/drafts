<?php

namespace App\Security\Handler\JsonLogin;

use Doctrine\ORM\EntityManagerInterface;
use App\Type\Note\NoteType;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SuccessHandler implements AuthenticationSuccessHandlerInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    ) {
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): ?Response
    {

        $apiToken = \md5(\rand(0, 10));
        $user = $token->getUser();
        $user->setApiToken($apiToken);
        $this->em->flush();

        return new JsonResponse([
            'apiToken' => $apiToken,
        ]);
    }
}
