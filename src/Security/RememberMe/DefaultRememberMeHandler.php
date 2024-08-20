<?php

namespace App\Security\RememberMe;

use Symfony\Component\Security\Core\Authentication\RememberMe\PersistentToken;
use Symfony\Component\Security\Http\RememberMe\AbstractRememberMeHandler;
use Symfony\Component\Security\Http\RememberMe\RememberMeDetails;
use Symfony\Component\Security\Core\User\UserInterface;

class DefaultRememberMeHandler extends AbstractRememberMeHandler {
	public function createRememberMeCookie(UserInterface $user): void
    {
        $series = random_bytes(66);
        $tokenValue = strtr(base64_encode(substr($series, 33)), '+/=', '-_~');
        $series = strtr(base64_encode(substr($series, 0, 33)), '+/=', '-_~');
        $token = new PersistentToken($user::class, $user->getUserIdentifier(), $series, $tokenValue, new \DateTimeImmutable());

        $this->createCookie(RememberMeDetails::fromPersistentToken($token, time() + 20));
    }
	
	public function processRememberMe(RememberMeDetails $rememberMeDetails, UserInterface $user): void
    {
        [$lastUsed, $series, $tokenValue, $class] = explode(':', $rememberMeDetails->getValue(), 4);
        $persistentToken = new PersistentToken($class, $rememberMeDetails->getUserIdentifier(), $series, $tokenValue, new \DateTimeImmutable('@'.$lastUsed));

        // if a token was regenerated less than a minute ago, there is no need to regenerate it
        // if multiple concurrent requests reauthenticate a user we do not want to update the token several times
        if ($persistentToken->getLastUsed()->getTimestamp() + 60 >= time()) {
            return;
        }

        $tokenValue = strtr(base64_encode(random_bytes(33)), '+/=', '-_~');
        $tokenLastUsed = new \DateTime();
        
        $this->createCookie($rememberMeDetails->withValue($series.':'.$tokenValue));
    }
}