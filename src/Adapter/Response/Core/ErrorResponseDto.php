<?php

declare(strict_types=1);

namespace App\Adapter\Response\Core;

readonly class ErrorResponseDto
{
    public function __construct(
        public string $resultCode,
        public string $message = '',
        /**
         * Пока не используется.
         *
         * @var null[]
         */
        public array $data = [],
    ) {
    }
}
