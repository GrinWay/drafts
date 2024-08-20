<?php

namespace App\Security\Authenticator\AccessTokenAuthenticator\TokenHandler;

use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

class DefaultTokenHandler implements AccessTokenHandlerInterface {
	public function __construct(
		private readonly UserRepository $userRepo,
    ) {
    }

    public function getUserBadgeFrom(string $accessToken): UserBadge
    {
		if (null === $accessToken) {
            throw new BadCredentialsException('Invalid credentials.');
        }
		
		$user = $this->userRepo->findOneBy(['apiToken' => $accessToken]);
		
		if (null === $user) {
            throw new UserNotFoundException('User was not found.');
        }
		
        return new UserBadge($user->getUserIdentifier());
    }

}