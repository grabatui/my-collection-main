<?php

declare(strict_types=1);

namespace App\Domain\Exception\Auth;

use App\Domain\Exception\DomainException;

class TooManyRequestsException extends DomainException
{
    public function getErrorCode(): string
    {
        return 'too_many_requests';
    }

    public function getErrorMessage(): ?string
    {
        return 'Слишком много попыток сброса пароля. Подождите перед следующей попыткой';
    }
}
