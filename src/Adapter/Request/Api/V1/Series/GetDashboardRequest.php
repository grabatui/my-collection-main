<?php

declare(strict_types=1);

namespace App\Adapter\Request\Api\V1\Series;

use Symfony\Component\Validator\Constraints as Assert;

class GetDashboardRequest
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Positive]
        public int $page,
    ) {}
}
