<?php

declare(strict_types=1);

namespace App\Domain\Exception;

class UnexpectedException extends DomainException
{
    public function getErrorCode(): string
    {
        return 'unexpected';
    }

    public function getErrorMessage(): ?string
    {
        return 'Непредвиденная ошибка';
    }
}
