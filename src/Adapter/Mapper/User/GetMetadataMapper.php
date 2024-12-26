<?php

declare(strict_types=1);

namespace App\Adapter\Mapper\User;

use App\Adapter\Response\User\GetMetadataResponse;
use App\Adapter\Response\User\GetMetadataResponse\GetMetadataResponseData;
use App\Domain\Entity\User;

class GetMetadataMapper
{
    public function fromEntityToResponse(User $user): GetMetadataResponse
    {
        return new GetMetadataResponse(
            data: new GetMetadataResponseData(
                id: (string)$user->getId(),
                name: $user->getName(),
                email: $user->getEmail(),
                roles: $user->getRoles(),
            ),
        );
    }
}
