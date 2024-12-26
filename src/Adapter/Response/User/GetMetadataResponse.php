<?php

declare(strict_types=1);

namespace App\Adapter\Response\User;

use App\Adapter\Response\Core\SuccessResponseDto;
use App\Adapter\Response\User\GetMetadataResponse\GetMetadataResponseData;

readonly class GetMetadataResponse extends SuccessResponseDto
{
    public function __construct(
        public GetMetadataResponseData $data,
        string $resultCode = 'success',
    ) {
        parent::__construct($resultCode);
    }
}
