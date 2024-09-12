<?php

namespace App\Security\Authenticator\GuardAuthenticator;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use KnpU\OAuth2ClientBundle\Client\Provider\FacebookClient;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class GitHubGuardAuthenticator extends SocialAuthenticator
{
    private $clientRegistry;
    private $em;
    private $router;

    public function __construct(ClientRegistry $clientRegistry, EntityManagerInterface $em, RouterInterface $router)
    {
        $this->clientRegistry = $clientRegistry;
        $this->em = $em;
        $this->router = $router;
    }

    public function supports(Request $request)
    {
        return 'app_github_login' === $request->attributes->get('_route');
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $client = $this->clientRegistry->getClient('github_main');
        $githubUser = $this->fetchUserFromToken($client);

        $email = $githubUser->getEmail();

        $user = $this->em->getRepository(User::class)->findOneBy(['email' => $email]);

        return $user;
    }
}
