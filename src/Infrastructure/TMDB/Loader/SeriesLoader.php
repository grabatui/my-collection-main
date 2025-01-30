<?php

declare(strict_types=1);

namespace App\Infrastructure\TMDB\Loader;

use App\Domain\Service\Series\Loader\Dto\ListCardDto;
use App\Domain\Service\Series\Loader\Dto\PaginationResultDto;
use App\Domain\Service\Series\Loader\SeriesLoaderInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Tmdb\Client;

readonly class SeriesLoader extends AbstractLoader implements SeriesLoaderInterface
{
    public function __construct(
        // TODO: Make abstract relation
        private Client $client,
        EventDispatcherInterface $eventDispatcher,
    ) {
        parent::__construct($eventDispatcher);
    }

    public function getTopRated(int $page = 1): PaginationResultDto
    {
        $this->setRequestLanguage();

        $result = $this->client->getTvApi()->getTopRated(['page' => $page]);

        return new PaginationResultDto(
            page: $result['page'],
            items: array_map(
                fn(array $item): ListCardDto => $this->makeListCardByArray($item),
                $result['results']
            ),
            totalPages: $result['total_pages'],
            totalResults: $result['total_results'],
        );
    }

    private function makeListCardByArray(array $data): ListCardDto
    {
        return new ListCardDto(
            id: $data['id'],
            name: $data['name'],
            isAdult: $data['adult'],
            backdropPath: $data['backdrop_path'],
            genreIds: $data['genre_ids'],
            originCountries: $data['origin_country'],
            originalLanguage: $data['original_language'],
            originalName: $data['original_name'],
            overview: $data['overview'],
            popularity: $data['popularity'],
            posterPath: $data['poster_path'],
            firstAirDate: $data['first_air_date'],
            voteAverage: $data['vote_average'],
            voteCount: $data['vote_count'],
        );
    }
}
