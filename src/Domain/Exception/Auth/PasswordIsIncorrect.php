<?php

declare(strict_types=1);

namespace App\Domain\Exception\Auth;

use App\Domain\Exception\DomainException;

class PasswordIsIncorrect extends DomainException
{
    public function getErrorCode(): string
    {
        return 'password_is_incorrect';
    }

    public function getErrorMessage(): ?string
    {
        return 'Пароли не совпадают';
    }
}
