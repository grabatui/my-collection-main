<?php

declare(strict_types=1);

namespace App\Adapter\Mapper\Series;

use App\Adapter\Response\Series\GetDashboardResponse;
use App\Adapter\Response\Series\GetDashboardResponse\GetDashboardResponseData;
use App\Adapter\Response\Series\GetDashboardResponse\GetDashboardResponseItem;
use App\Domain\Repository\CountryRepository;
use App\Domain\Repository\GenreRepository;
use App\Domain\Service\Series\Loader\Dto\ListCardDto;

class GetDashboardMapper
{
    public function __construct(
        private readonly CountryRepository $countryRepository,
        private readonly GenreRepository $genreRepository,
    ) {}

    /**
     * @param ListCardDto[] $cards
     */
    public function fromDtoToResponse(array $cards): GetDashboardResponse
    {
        $countries = $this->countryRepository->getAllByCodes();
        $genres = $this->genreRepository->getAllByExternalIds();

        return new GetDashboardResponse(
            data: new GetDashboardResponseData(
                items: array_map(
                    static fn(ListCardDto $card): GetDashboardResponseItem => new GetDashboardResponseItem(
                        id: $card->id,
                        title: $card->name,
                        originalTitle: $card->originalName,
                        genres: array_filter(
                            array_map(
                                static fn(int $genreId): ?string => $genres[$genreId]?->getName(),
                                $card->genreIds,
                            )
                        ),
                        firstAirDate: $card->firstAirDate,
                        countries: array_filter(
                            array_map(
                                static fn(string $countryCode): ?string => $countries[$countryCode]?->getName(),
                                $card->originCountries,
                            )
                        ),
                        overview: $card->overview,
                        voteAverage: $card->voteAverage,
                        posterPath: $card->getPosterUrlWithSize(),
                    ),
                    $cards,
                )
            ),
        );
    }
}
