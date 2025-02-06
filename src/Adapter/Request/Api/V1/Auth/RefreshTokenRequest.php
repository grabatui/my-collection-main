<?php

declare(strict_types=1);

namespace App\Adapter\Request\Api\V1\Auth;

use Symfony\Component\Validator\Constraints as Assert;

class RefreshTokenRequest
{
    public function __construct(
        #[Assert\NotBlank]
        public string $refreshToken,
    ) {
    }
}
