<?php

declare(strict_types=1);

namespace App\Adapter\Response\Core\Series;

class SeriesCardResponseDto
{
    /**
     * @param string[] $genres
     * @param string[] $countries
     */
    public function __construct(
        public int $id,
        public string $title,
        public ?string $originalTitle,
        public array $genres,
        public string $firstAirDate,
        public array $countries,
        public string $overview,
        public float $voteAverage,
        public string $posterPath,
        public string $slug,
    ) {}
}
