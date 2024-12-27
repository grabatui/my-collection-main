<?php

declare(strict_types=1);

namespace App\Adapter\Response\Core;

readonly class SuccessResponseDto
{
    public function __construct(
        public string $resultCode = 'success',
        public ?string $message = null,
    ) {
    }
}
