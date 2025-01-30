<?php

declare(strict_types=1);

namespace App\Domain\Service\Series\Loader\Dto;

class ListCardDto extends AbstractListCardDto
{
    public function getPosterUrlWithSize(int $size = 200): string
    {
        return sprintf('https://image.tmdb.org/t/p/w%d%s', $size, $this->posterPath);
    }

    public function makeSlug(): string
    {
        return 'tmdb-' . $this->id;
    }
}
