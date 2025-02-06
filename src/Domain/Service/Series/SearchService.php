<?php

declare(strict_types=1);

namespace App\Domain\Service\Series;

use App\Domain\Service\Series\Loader\Dto\PaginationResultDto;
use App\Domain\Service\Series\Loader\SeriesLoaderInterface;

readonly class SearchService
{
    public function __construct(
        private SeriesLoaderInterface $seriesLoader,
    ) {}

    public function execute(string $query, int $page): PaginationResultDto
    {
        return $this->seriesLoader->search($query, $page);
    }
}
