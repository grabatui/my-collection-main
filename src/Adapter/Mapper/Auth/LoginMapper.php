<?php

declare(strict_types=1);

namespace App\Adapter\Mapper\Auth;

use App\Adapter\Request\Api\V1\Auth\LoginRequest;
use App\Adapter\Response\Auth\LoginResponse;
use App\Adapter\Response\Auth\LoginResponse\LoginResponseData;
use App\Domain\Entity\AccessToken;
use App\Domain\Service\Auth\Login\Dto\LoginDto;

class LoginMapper
{
    public function fromQueryToDto(LoginRequest $request): LoginDto
    {
        return new LoginDto(
            email: $request->email,
            password: $request->password,
        );
    }

    public function fromDtoToResponse(AccessToken $accessToken): LoginResponse
    {
        return new LoginResponse(
            data: new LoginResponseData(
                accessToken: $accessToken->getAccessToken(),
                refreshToken: $accessToken->getRefreshToken(),
            ),
        );
    }
}
