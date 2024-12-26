<?php

declare(strict_types=1);

namespace App\Adapter\Response\Auth\RegisterResponse;

readonly class RegisterResponseData
{
    public function __construct(
        public string $accessToken,
    ) {
    }
}
