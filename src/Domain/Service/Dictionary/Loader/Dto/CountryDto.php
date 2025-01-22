<?php

declare(strict_types=1);

namespace App\Domain\Service\Dictionary\Loader\Dto;

class CountryDto
{
    public function __construct(
        public string $iso3166Code,
        public string $nativeName,
        public string $englishName,
    ) {}
}
