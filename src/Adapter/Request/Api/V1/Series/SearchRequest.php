<?php

declare(strict_types=1);

namespace App\Adapter\Request\Api\V1\Series;

use Symfony\Component\Validator\Constraints as Assert;

class SearchRequest
{
    public function __construct(
        #[Assert\NotBlank]
        public string $query,

        #[Assert\NotBlank]
        #[Assert\Positive]
        public int $page,
    ) {
    }
}
