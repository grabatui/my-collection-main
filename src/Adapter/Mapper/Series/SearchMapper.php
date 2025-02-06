<?php

declare(strict_types=1);

namespace App\Adapter\Mapper\Series;

use App\Adapter\Mapper\Core\Series\SeriesCardMapper;
use App\Adapter\Response\Series\SearchResponse;
use App\Adapter\Response\Series\SearchResponse\SearchResponseData;
use App\Domain\Service\Series\Loader\Dto\PaginationResultDto;

class SearchMapper
{
    public function __construct(
        private readonly SeriesCardMapper $seriesCardMapper,
    ) {
    }

    public function fromDtoToResponse(PaginationResultDto $result): SearchResponse
    {
        return new SearchResponse(
            data: new SearchResponseData(
                page: $result->page,
                items: $this->seriesCardMapper->fromAbstractListCardsToSeriesCardResponse($result->items),
                totalPages: $result->totalPages,
                totalResults: $result->totalResults,
            ),
        );
    }
}
