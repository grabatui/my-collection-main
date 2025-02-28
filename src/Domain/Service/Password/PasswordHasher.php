<?php

declare(strict_types=1);

namespace App\Domain\Service\Password;

class PasswordHasher
{
    public function __construct(
        private readonly string $salt,
    ) {
    }

    public function execute(string $originalPassword): string
    {
        return password_hash(
            password: $this->wrapPasswordWithSalt($originalPassword),
            algo: PASSWORD_DEFAULT,
        );
    }

    public function verify(string $originalPassword, string $passwordHash): bool
    {
        return password_verify(
            password: $this->wrapPasswordWithSalt($originalPassword),
            hash: $passwordHash,
        );
    }

    private function wrapPasswordWithSalt(string $password): string
    {
        return $this->salt.$password.$this->salt;
    }
}
