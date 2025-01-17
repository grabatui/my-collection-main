<?php

declare(strict_types=1);

namespace App\Domain\Service\Series;

use App\Domain\Service\Cache\CacheServiceInterface;
use App\Domain\Service\Cache\Dto\CacheKeyDto;
use App\Domain\Service\Cache\Enum\CacheKeyEnum;
use Grabatui\MyShowsScrapper\Series\Dto\Search\CardDto;
use Grabatui\MyShowsScrapper\Series\Enum\DirectionEnum;
use Grabatui\MyShowsScrapper\Series\Enum\SortEnum;
use Grabatui\MyShowsScrapper\SeriesManager;

readonly class GetDashboardService
{
    private const SortEnum DEFAULT_SORT = SortEnum::WATCHING;
    private const DirectionEnum DEFAULT_DIRECTION = DirectionEnum::DESC;

    public function __construct(
        private CacheServiceInterface $cacheService,
        private SeriesManager $seriesManager,
    ) {}

    /**
     * @return CardDto[]
     */
    public function execute(
        int $page,
        ?SortEnum $sort = null,
        ?DirectionEnum $direction = null,
    ): array {
        $sort ??= self::DEFAULT_SORT;
        $direction ??= self::DEFAULT_DIRECTION;

        $cacheKey = new CacheKeyDto(
            key: CacheKeyEnum::DASHBOARD,
            id: implode('-', [$page, $sort->value, $direction->value]),
        );

        return $this->cacheService->get($cacheKey, function () use ($page, $sort, $direction) {
            return $this->seriesManager->search(
                sort: $sort,
                direction: $direction,
                page: $page,
            );
        });
    }
}
