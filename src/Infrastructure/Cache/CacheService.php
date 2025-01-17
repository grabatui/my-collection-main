<?php

declare(strict_types=1);

namespace App\Infrastructure\Cache;

use App\Domain\Service\Cache\CacheServiceInterface;
use App\Domain\Service\Cache\Dto\CacheKeyDto;
use Symfony\Contracts\Cache\CacheInterface;

class CacheService implements CacheServiceInterface
{
    public function __construct(
        private readonly CacheInterface $cache,
    ) {}

    public function get(CacheKeyDto $key, callable $callback): mixed
    {
        return $this->cache->get(
            key: (string)$key,
            callback: $callback,
        );
    }

    public function delete(CacheKeyDto $key): void
    {
        $this->cache->delete((string)$key);
    }
}
