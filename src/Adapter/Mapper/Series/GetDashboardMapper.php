<?php

declare(strict_types=1);

namespace App\Adapter\Mapper\Series;

use App\Adapter\Mapper\Core\Series\SeriesCardMapper;
use App\Adapter\Response\Series\GetDashboardResponse;
use App\Adapter\Response\Series\GetDashboardResponse\GetDashboardResponseData;
use App\Domain\Service\Series\Loader\Dto\PaginationResultDto;

class GetDashboardMapper
{
    public function __construct(
        private readonly SeriesCardMapper $seriesCardMapper,
    ) {
    }

    public function fromDtoToResponse(PaginationResultDto $result): GetDashboardResponse
    {
        return new GetDashboardResponse(
            data: new GetDashboardResponseData(
                page: $result->page,
                items: $this->seriesCardMapper->fromAbstractListCardsToSeriesCardResponse($result->items),
                totalPages: $result->totalPages,
                totalResults: $result->totalResults,
            ),
        );
    }
}
