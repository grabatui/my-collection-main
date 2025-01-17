<?php

declare(strict_types=1);

namespace App\Adapter\Response\Series\GetDashboardResponse;

class GetDashboardResponseItem
{
    /**
     * @param string[] $genres
     */
    public function __construct(
        public int $id,
        public string $ruTitle,
        public ?string $enTitle,
        public array $genres,
        public int $year,
        public string $country,
        public string $status,
        public string $rawLink,
        public string $posterUrl,
        public float $rating,
    ) {}
}
