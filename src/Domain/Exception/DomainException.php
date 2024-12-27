<?php

declare(strict_types=1);

namespace App\Domain\Exception;

use DomainException as BaseDomainException;

abstract class DomainException extends BaseDomainException
{
    abstract public function getErrorCode(): string;

    public function getErrorMessage(): ?string
    {
        return null;
    }
}
