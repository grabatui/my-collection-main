<?php

declare(strict_types=1);

namespace App\Domain\Service\Series;

use App\Domain\Service\Cache\CacheServiceInterface;
use App\Domain\Service\Cache\Dto\CacheKeyDto;
use App\Domain\Service\Cache\Enum\CacheKeyEnum;
use App\Domain\Service\Series\Loader\Dto\PaginationResultDto;
use App\Domain\Service\Series\Loader\SeriesLoaderInterface;

readonly class GetDashboardService
{
    public function __construct(
        private CacheServiceInterface $cacheService,
        private SeriesLoaderInterface $seriesLoader,
    ) {}

    public function execute(int $page): PaginationResultDto
    {
        $cacheKey = new CacheKeyDto(
            key: CacheKeyEnum::DASHBOARD,
            id: implode('-', [$page]),
        );

        return $this->cacheService->get($cacheKey, function () use ($page) {
            return $this->seriesLoader->getTopRated($page);
        });
    }
}
