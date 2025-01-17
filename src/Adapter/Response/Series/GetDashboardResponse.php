<?php

declare(strict_types=1);

namespace App\Adapter\Response\Series;

use App\Adapter\Response\Core\SuccessResponseDto;
use App\Adapter\Response\Series\GetDashboardResponse\GetDashboardResponseData;

readonly class GetDashboardResponse extends SuccessResponseDto
{
    public function __construct(
        public GetDashboardResponseData $data,
        string $resultCode = 'success',
    ) {
        parent::__construct($resultCode);
    }
}
