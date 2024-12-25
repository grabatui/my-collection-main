<?php

declare(strict_types=1);

namespace App\Adapter\Response\Core\Dto;

readonly class ValidationErrorViolationDto
{
    public function __construct(
        public string $path,
        public string $message,
    ) {
    }
}