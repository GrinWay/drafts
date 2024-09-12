<?php

namespace App\Security\RequestMatcher;

use Symfony\Component\HttpFoundation\RequestMatcherInterface;
use Symfony\Component\HttpFoundation\Request;

class CustomRequestMatcher implements RequestMatcherInterface
{
    public function matches(Request $request): bool
    {
        return 'GET' === $request->getMethod() || 'POST' === $request->getMethod();
    }
}
