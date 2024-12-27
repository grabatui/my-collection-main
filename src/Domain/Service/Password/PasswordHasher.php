<?php

declare(strict_types=1);

namespace App\Domain\Service\Password;

readonly class PasswordHasher
{
    public function __construct(
        private string $salt,
    ) {
    }

    public function execute(string $originalPassword): string
    {
        return password_hash(
            password: $this->salt.$originalPassword.$this->salt,
            algo: PASSWORD_DEFAULT,
        );
    }
}
