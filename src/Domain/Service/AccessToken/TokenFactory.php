<?php

declare(strict_types=1);

namespace App\Domain\Service\AccessToken;

use App\Domain\Entity\User;
use ReallySimpleJWT\Token;

class TokenFactory
{
    private const string DEFAULT_ISSUER = 'localhost';

    public function __construct(
        private readonly string $secret,
        private readonly int $tokenTtl,
    ) {
    }

    public function generate(User $user): string
    {
        return Token::create(
            userId: $user->getUserIdentifier(),
            secret: $this->secret,
            expiration: time() + $this->tokenTtl,
            issuer: self::DEFAULT_ISSUER,
        );
    }
}
