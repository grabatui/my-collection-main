<?php

declare(strict_types=1);

namespace App\Domain\Service\Auth\Login;

use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Symfony\Component\RateLimiter\RateLimiterFactory;

readonly class LoginRateLimiter
{
    public function __construct(
        private RateLimiterFactory $anonymousApiLimiter,
    ) {}

    public function acceptByClientIp(string $clientIp): void
    {
        $limiter = $this->anonymousApiLimiter->create($clientIp);

        if (!$limiter->consume()->isAccepted()) {
            throw new TooManyRequestsHttpException();
        }
    }
}
