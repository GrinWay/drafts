<?php

namespace App\Security\Authenticator\Auth2FA\ProviderDecider;

use Scheb\TwoFactorBundle\Security\Authentication\Token\TwoFactorTokenInterface;
use Scheb\TwoFactorBundle\Security\TwoFactor\AuthenticationContextInterface;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\TwoFactorProviderDeciderInterface;

class TwoFactorProviderDecider implements TwoFactorProviderDeciderInterface
{
    public function getPreferredTwoFactorProvider(array $activeProviders, TwoFactorTokenInterface $token, AuthenticationContextInterface $context): string|null
    {
        //\dd($activeProviders);
    }
}
