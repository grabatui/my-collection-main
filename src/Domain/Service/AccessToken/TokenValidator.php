<?php

declare(strict_types=1);

namespace App\Domain\Service\AccessToken;

use ReallySimpleJWT\Token;

class TokenValidator
{
    public function __construct(
        private readonly string $secret,
    ) {
    }

    public function validate(string $token): bool
    {
        return Token::validate($token, $this->secret) && Token::validateExpiration($token);
    }
}
