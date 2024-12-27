<?php

declare(strict_types=1);

namespace App\Domain\Exception\User;

use App\Domain\Exception\DomainException;

class UserNotFoundException extends DomainException
{
    public function getErrorCode(): string
    {
        return 'user_not_found';
    }

    public function getErrorMessage(): ?string
    {
        return 'Пользователь не найден';
    }
}
