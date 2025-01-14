<?php

declare(strict_types=1);

namespace App\Adapter\Response\Auth\RefreshTokenResponse;

class RefreshTokenResponseData
{
    public function __construct(
        public string $accessToken,
        public string $refreshToken,
    ) {
    }
}
