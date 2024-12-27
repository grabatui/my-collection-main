<?php

declare(strict_types=1);

namespace App\Adapter\Response\Auth\LoginResponse;

readonly class LoginResponseData
{
    public function __construct(
        public string $accessToken,
    ) {
    }
}
