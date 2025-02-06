<?php

declare(strict_types=1);

namespace App\Adapter\Response\Series\SearchResponse;

use App\Adapter\Response\Core\Series\SeriesCardResponseDto;

class SearchResponseData
{
    /**
     * @param SeriesCardResponseDto[] $items
     */
    public function __construct(
        public int $page,
        public array $items,
        public int $totalPages,
        public int $totalResults,
    ) {
    }
}
