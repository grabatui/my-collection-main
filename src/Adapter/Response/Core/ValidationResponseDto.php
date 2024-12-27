<?php

declare(strict_types=1);

namespace App\Adapter\Response\Core;

use App\Adapter\Response\Core\Dto\ValidationErrorViolationDto;

readonly class ValidationResponseDto
{
    /**
     * @param ValidationErrorViolationDto[] $data
     */
    public function __construct(
        public string $resultCode,
        public string $message = '',
        public array $data = [],
    ) {
    }
}
