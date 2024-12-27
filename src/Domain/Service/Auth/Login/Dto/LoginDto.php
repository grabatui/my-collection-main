<?php

declare(strict_types=1);

namespace App\Domain\Service\Auth\Login\Dto;

class LoginDto
{
    public function __construct(
        public string $email,
        public string $password,
    ) {
    }
}
