<?php

declare(strict_types=1);

namespace App\Domain\Service\AccessToken;

use App\Domain\Entity\User;
use ReallySimpleJWT\Token;

class TokenFactory
{
    private const string DEFAULT_ISSUER = 'localhost';

    private const int ADDITIONAL_EXPIRATION_IN_SECONDS = 3600;

    public function __construct(
        private readonly string $secret,
        private readonly int $tokenTtl,
    ) {
    }

    public function generateAccessToken(User $user): string
    {
        return Token::create(
            userId: $user->getUserIdentifier(),
            secret: $this->secret,
            expiration: time() + $this->tokenTtl,
            issuer: self::DEFAULT_ISSUER,
        );
    }

    public function generateRefreshToken(User $user): string
    {
        return Token::create(
            userId: $user->getUserIdentifier(),
            secret: $this->secret,
            expiration: time() + $this->tokenTtl + self::ADDITIONAL_EXPIRATION_IN_SECONDS,
            issuer: self::DEFAULT_ISSUER,
        );
    }
}
