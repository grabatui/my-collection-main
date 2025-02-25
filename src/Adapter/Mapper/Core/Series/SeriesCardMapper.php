<?php

declare(strict_types=1);

namespace App\Adapter\Mapper\Core\Series;

use App\Adapter\Response\Core\Series\SeriesCardResponseDto;
use App\Domain\Repository\CountryRepository;
use App\Domain\Repository\GenreRepository;
use App\Domain\Service\Series\Loader\Dto\AbstractListCardDto;

class SeriesCardMapper
{
    private const int OVERVIEW_MAX_LENGTH = 250;

    public function __construct(
        private readonly CountryRepository $countryRepository,
        private readonly GenreRepository $genreRepository,
    ) {
    }

    /**
     * @param AbstractListCardDto[] $cards
     *
     * @return SeriesCardResponseDto[]
     */
    public function fromAbstractListCardsToSeriesCardResponse(array $cards): array
    {
        $countries = $this->countryRepository->getAllByCodes();
        $genres = $this->genreRepository->getAllByExternalIds();

        return array_map(
            fn (AbstractListCardDto $card): SeriesCardResponseDto => new SeriesCardResponseDto(
                id: $card->id,
                title: $card->name,
                originalTitle: $card->originalName,
                genres: array_filter(
                    array_map(
                        static fn (int $genreId): string => $genres[$genreId]->getName(),
                        $card->genreIds,
                    )
                ),
                firstAirDate: $card->firstAirDate,
                countries: array_filter(
                    array_map(
                        static fn (string $countryCode): string => $countries[$countryCode]->getName(),
                        $card->originCountries,
                    )
                ),
                overview: $this->cutOverview($card->overview),
                voteAverage: $card->voteAverage,
                posterPath: $card->getPosterUrlWithSize(),
                slug: $card->makeSlug(),
            ),
            $cards,
        );
    }

    private function cutOverview(string $overview): string
    {
        if (mb_strlen($overview) > self::OVERVIEW_MAX_LENGTH) {
            $overview = mb_substr($overview, 0, self::OVERVIEW_MAX_LENGTH);

            if (!in_array(mb_substr($overview, -1), [' ', '.', ','], true)) {
                $lastSpace = mb_strrpos($overview, ' ');

                $overview = mb_substr($overview, 0, $lastSpace);
            }

            $overview = trim($overview, ' ,.');

            $overview .= '&hellip;';
        }

        return $overview;
    }
}
