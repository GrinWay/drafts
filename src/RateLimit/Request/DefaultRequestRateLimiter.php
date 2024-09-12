<?php

namespace App\RateLimit\Request;

use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RateLimiter\AbstractRequestRateLimiter;

class DefaultRequestRateLimiter extends AbstractRequestRateLimiter
{
    public function __construct(
        private readonly RateLimiterFactory $defaultRequestLimiter,
    ) {
    }

    protected function getLimiters(Request $request): array
    {
        return [
            $this->defaultRequestLimiter->create($request->getClientIp()),
        ];
    }
}
