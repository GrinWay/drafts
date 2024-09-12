<?php

namespace App\Security\AuthenticationToken;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;
use Symfony\Component\Security\Http\Authenticator\Token\PostAuthenticationToken;
use Symfony\Component\Security\Core\User\UserInterface;

class FormLoginAuthenticationToken extends PostAuthenticationToken
{
    protected readonly string $token;

    public function __construct(
        UserInterface $user,
        string $firewallName,
        array $roles,
        ?string $token,
    ) {
        $this->token = $token ?? '';

        parent::__construct(
            $user,
            $firewallName,
            $roles,
        );
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function __serialize(): array
    {
        return [$this->token, parent::__serialize()];
    }

    public function __unserialize(array $data): void
    {
        [$this->token, $parentData] = $data;
        parent::__unserialize($parentData);
    }
}
