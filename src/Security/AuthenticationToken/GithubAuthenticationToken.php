<?php

namespace App\Security\AuthenticationToken;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;
use Symfony\Component\Security\Http\Authenticator\Token\PostAuthenticationToken;
use Symfony\Component\Security\Core\User\UserInterface;

class GithubAuthenticationToken extends PostAuthenticationToken
{
}
