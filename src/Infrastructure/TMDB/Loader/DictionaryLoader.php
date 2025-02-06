<?php

declare(strict_types=1);

namespace App\Infrastructure\TMDB\Loader;

use App\Domain\Service\Dictionary\Loader\DictionaryLoaderInterface;
use App\Domain\Service\Dictionary\Loader\Dto\CountryDto;
use App\Domain\Service\Dictionary\Loader\Dto\GenreDto;
use App\Infrastructure\TMDB\Api\ConfigurationApi;
use Psr\EventDispatcher\EventDispatcherInterface;
use Tmdb\Client;

readonly class DictionaryLoader extends AbstractLoader implements DictionaryLoaderInterface
{
    public function __construct(
        private Client $client,
        EventDispatcherInterface $eventDispatcher,
    ) {
        parent::__construct($eventDispatcher);
    }

    public function getCountries(): array
    {
        $this->setRequestLanguage();
        $result = (new ConfigurationApi($this->client))->getCountries();

        return array_map(
            static fn (array $item): CountryDto => new CountryDto(
                iso3166Code: $item['iso_3166_1'],
                nativeName: $item['native_name'],
                englishName: $item['english_name'],
            ),
            $result
        );
    }

    public function getMovieGenres(): array
    {
        $this->setRequestLanguage();
        $result = $this->client->getGenresApi()->getMovieGenres();

        return array_map(
            static fn (array $item): GenreDto => new GenreDto(
                id: $item['id'],
                name: $item['name'],
            ),
            $result['genres'] ?? []
        );
    }

    public function getSeriesGenres(): array
    {
        $this->setRequestLanguage();
        $result = $this->client->getGenresApi()->getTvGenres();

        return array_map(
            static fn (array $item): GenreDto => new GenreDto(
                id: $item['id'],
                name: $item['name'],
            ),
            $result['genres'] ?? []
        );
    }
}
