<?php

declare(strict_types=1);

namespace App\Domain\Service\Auth;

use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Symfony\Component\RateLimiter\RateLimiterFactory;

class AuthRateLimiter
{
    public function __construct(
        private readonly RateLimiterFactory $anonymousApiLimiter,
    ) {
    }

    public function acceptByClientIp(string $clientIp): void
    {
        $limiter = $this->anonymousApiLimiter->create($clientIp);

        if (!$limiter->consume()->isAccepted()) {
            throw new TooManyRequestsHttpException();
        }
    }
}
