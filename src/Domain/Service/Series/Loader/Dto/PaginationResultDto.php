<?php

declare(strict_types=1);

namespace App\Domain\Service\Series\Loader\Dto;

class PaginationResultDto
{
    public function __construct(
        public int $page,
        public array $items,
        public int $totalPages,
        public int $totalResults,
    ) {}
}
