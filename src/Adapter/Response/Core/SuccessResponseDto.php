<?php

declare(strict_types=1);

namespace App\Adapter\Response\Core;

use OpenApi\Attributes as OA;

readonly class SuccessResponseDto
{
    public function __construct(
        #[OA\Property(enum: ['success', 'error'])]
        public string $resultCode = 'success',
        public ?string $message = null,
    ) {
    }
}
