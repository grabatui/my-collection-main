<?php

declare(strict_types=1);

namespace App\Domain\Service\Cache\Dto;

use App\Domain\Service\Cache\Enum\CacheKeyEnum;

class CacheKeyDto
{
    public function __construct(
        public CacheKeyEnum $key,
        public string $id,
    ) {
    }

    public function __toString(): string
    {
        return sprintf('%s-%s', $this->key->value, $this->id);
    }
}
