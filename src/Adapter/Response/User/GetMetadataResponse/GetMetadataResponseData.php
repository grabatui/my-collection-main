<?php

declare(strict_types=1);

namespace App\Adapter\Response\User\GetMetadataResponse;

readonly class GetMetadataResponseData
{
    /**
     * @param string[] $roles
     */
    public function __construct(
        public string $id,
        public string $name,
        public string $email,
        public array $roles,
    ) {
    }
}
