<?php

declare(strict_types=1);

namespace App\Adapter\Request\Api\V1\Series;

use Grabatui\MyShowsScrapper\Series\Enum\DirectionEnum;
use Grabatui\MyShowsScrapper\Series\Enum\SortEnum;
use Symfony\Component\Validator\Constraints as Assert;

class GetDashboardRequest
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Positive]
        public int $page,

        #[Assert\Choice(callback: [SortEnum::class, 'values'])]
        public ?string $sort,

        #[Assert\Choice(callback: [DirectionEnum::class, 'values'])]
        public ?string $direction,
    ) {}
}
