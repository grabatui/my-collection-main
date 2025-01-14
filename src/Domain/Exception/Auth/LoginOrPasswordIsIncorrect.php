<?php

declare(strict_types=1);

namespace App\Domain\Exception\Auth;

use App\Domain\Exception\DomainException;

class LoginOrPasswordIsIncorrect extends DomainException
{
    public function getErrorCode(): string
    {
        return 'login_or_password_is_incorrect';
    }

    public function getErrorMessage(): ?string
    {
        return 'Пользователь или пароль неверный';
    }
}
