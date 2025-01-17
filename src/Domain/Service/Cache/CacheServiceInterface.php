<?php

declare(strict_types=1);

namespace App\Domain\Service\Cache;

use App\Domain\Service\Cache\Dto\CacheKeyDto;

interface CacheServiceInterface
{
    public function get(CacheKeyDto $key, callable $callback): mixed;
}
