<?php

declare(strict_types=1);

namespace App\Adapter\Mapper\Auth;

use App\Adapter\Request\Api\V1\Auth\RegisterRequest;
use App\Adapter\Response\Auth\RegisterResponse;
use App\Adapter\Response\Auth\RegisterResponse\RegisterResponseData;
use App\Domain\Entity\AccessToken;
use App\Domain\Service\Auth\Registration\Dto\RegisterDto;

class RegisterMapper
{
    public function fromQueryToDto(RegisterRequest $request): RegisterDto
    {
        return new RegisterDto(
            email: $request->email,
            name: $request->name,
            password: $request->password,
        );
    }

    public function fromDtoToResponse(AccessToken $accessToken): RegisterResponse
    {
        return new RegisterResponse(
            data: new RegisterResponseData(
                accessToken: $accessToken->getAccessToken(),
                refreshToken: $accessToken->getRefreshToken(),
            ),
        );
    }
}
