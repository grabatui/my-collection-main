<?php

declare(strict_types=1);

namespace App\Domain\Exception\Auth;

use App\Domain\Exception\DomainException;

class TokenIsInvalidException extends DomainException
{
    public function getErrorCode(): string
    {
        return 'token_is_invalid';
    }

    public function getErrorMessage(): ?string
    {
        return 'Токен не валиден';
    }
}
