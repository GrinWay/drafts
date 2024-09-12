<?php

namespace App\Security\RequestMatcher;

use function Symfony\component\string\u;

use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;
use Symfony\Component\HttpFoundation\Request;

class UrlRequestMatcher implements RequestMatcherInterface
{
    public function matches(Request $request): bool
    {
        return \preg_match('~^/$~', $request->getPathInfo());
    }
}
