<?php

declare(strict_types=1);

namespace App\Adapter\Mapper\Series;

use App\Adapter\Response\Series\GetDashboardResponse;
use App\Adapter\Response\Series\GetDashboardResponse\GetDashboardResponseData;
use App\Adapter\Response\Series\GetDashboardResponse\GetDashboardResponseItem;
use Grabatui\MyShowsScrapper\Series\Dto\Search\CardDto;

class GetDashboardMapper
{
    /**
     * @param CardDto[] $cards
     */
    public function fromDtoToResponse(array $cards): GetDashboardResponse
    {
        return new GetDashboardResponse(
            data: new GetDashboardResponseData(
                items: array_map(
                    static fn(CardDto $card): GetDashboardResponseItem => new GetDashboardResponseItem(
                        id: $card->id,
                        ruTitle: $card->ruTitle,
                        enTitle: $card->enTitle,
                        genres: $card->genres,
                        year: $card->year,
                        country: $card->country,
                        status: $card->status->value,
                        rawLink: $card->rawLink,
                        posterUrl: $card->posterUrl,
                        rating: $card->rating,
                    ),
                    $cards,
                )
            ),
        );
    }
}
