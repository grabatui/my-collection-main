<?php

declare(strict_types=1);

namespace App\Domain\Service\Dictionary\Loader;

use App\Domain\Service\Dictionary\Loader\Dto\CountryDto;
use App\Domain\Service\Dictionary\Loader\Dto\GenreDto;

interface DictionaryLoaderInterface
{
    /**
     * @return CountryDto[]
     */
    public function getCountries(): array;

    /**
     * @return GenreDto[]
     */
    public function getMovieGenres(): array;

    /**
     * @return GenreDto[]
     */
    public function getSeriesGenres(): array;
}
