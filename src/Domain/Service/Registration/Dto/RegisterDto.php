<?php

declare(strict_types=1);

namespace App\Domain\Service\Registration\Dto;

class RegisterDto
{
    public function __construct(
        public string $email,
        public string $name,
        public string $password,
    ) {}
}
