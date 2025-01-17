<?php

declare(strict_types=1);

namespace App\Adapter\Response\Series\GetDashboardResponse;

class GetDashboardResponseData
{
    /**
     * @param GetDashboardResponseItem[] $items
     */
    public function __construct(
        public array $items,
    ) {}
}
