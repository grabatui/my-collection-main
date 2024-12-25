<?php

declare(strict_types=1);

namespace App\Adapter\Response\Auth\RegisterResponse;

use OpenApi\Attributes as OA;

readonly class RegisterResponseData
{
    public function __construct(
        #[OA\Property(
            description: 'Токен авторизации',
        )]
        public string $accessToken,
    ) {
    }
}
