<?php

declare(strict_types=1);

namespace App\Infrastructure\Cache;

use App\Domain\Service\Cache\CacheServiceInterface;
use App\Domain\Service\Cache\Dto\CacheKeyDto;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class CacheService implements CacheServiceInterface
{
    public function __construct(
        private readonly CacheInterface $cache,
        private readonly int $defaultExpireTime,
    ) {}

    public function get(CacheKeyDto $key, callable $callback, ?int $expireTime = null): mixed
    {
        $expireTime = $expireTime ?? $this->defaultExpireTime;

        return $this->cache->get((string)$key, function (ItemInterface $item) use ($callback, $expireTime) {
            $item->expiresAfter($expireTime);

            return $callback();
        });
    }

    public function delete(CacheKeyDto $key): void
    {
        $this->cache->delete((string)$key);
    }
}
