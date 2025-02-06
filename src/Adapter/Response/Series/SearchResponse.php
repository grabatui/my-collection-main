<?php

declare(strict_types=1);

namespace App\Adapter\Response\Series;

use App\Adapter\Response\Core\SuccessResponseDto;
use App\Adapter\Response\Series\SearchResponse\SearchResponseData;

readonly class SearchResponse extends SuccessResponseDto
{
    public function __construct(
        public SearchResponseData $data,
        string $resultCode = 'success',
    ) {
        parent::__construct($resultCode);
    }
}
