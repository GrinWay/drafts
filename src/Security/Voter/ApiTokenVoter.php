<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\NullToken;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Repository\UserRepository;
use Symfony\Component\Security\Http\Authenticator\Token\PostAuthenticationToken;

class ApiTokenVoter extends Voter
{
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly UserRepository $userRepo,
    ) {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return 'VOTE_API_TOKEN' === $attribute && null !== $this->requestStack->getCurrentRequest();
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {

        if ($token instanceof PostAuthenticationToken) {
            return true;
        }

        //\dd($token->());

        return false;



        $request = $this->requestStack->getCurrentRequest();
        $requestApiToken = $request->query->get('apiToken') ?? $request->getPayload()->get('apiToken');
        if (null === $requestApiToken) {
            return false;
        }

        $user = $this->userRepo->findOneBy(['apiToken' => $requestApiToken]);
        if (null === $user) {
            return false;
        }

        $userApiToken = $user->getApiToken();
        if (null === $userApiToken) {
            return false;
        }

        if ($userApiToken != $requestApiToken) {
            return false;
        }

        return true;
    }
}
