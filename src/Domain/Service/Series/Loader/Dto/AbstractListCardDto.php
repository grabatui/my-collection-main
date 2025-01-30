<?php

declare(strict_types=1);

namespace App\Domain\Service\Series\Loader\Dto;

abstract class AbstractListCardDto
{
    /**
     * @param int[] $genreIds
     * @param string[] $originCountries
     */
    public function __construct(
        public int $id,
        public string $name,
        public bool $isAdult,
        public string $backdropPath,
        public array $genreIds,
        public array $originCountries,
        public string $originalLanguage,
        public string $originalName,
        public string $overview,
        public float $popularity,
        public string $posterPath,
        public string $firstAirDate,
        public float $voteAverage,
        public int $voteCount,
    ) {}

    abstract public function getPosterUrlWithSize(int $size = 200): string;

    abstract public function makeSlug(): string;
}
