<?php

declare(strict_types=1);

namespace App\Domain\Exception\Auth;

use App\Domain\Exception\DomainException;

class ResetPasswordException extends DomainException
{
    public function getErrorCode(): string
    {
        return 'reset_password';
    }

    public function getErrorMessage(): ?string
    {
        return 'Ошибка во время восстановления пароля: ' . $this->getMessage();
    }
}
