<?php

declare(strict_types=1);

namespace App\Domain\Service\Series\Loader;

use App\Domain\Service\Series\Loader\Dto\PaginationResultDto;

interface SeriesLoaderInterface
{
    public function getTopRated(int $page = 1): PaginationResultDto;
}
