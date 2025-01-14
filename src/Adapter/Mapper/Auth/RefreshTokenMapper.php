<?php

declare(strict_types=1);

namespace App\Adapter\Mapper\Auth;

use App\Adapter\Response\Auth\RefreshTokenResponse;
use App\Adapter\Response\Auth\RefreshTokenResponse\RefreshTokenResponseData;
use App\Domain\Entity\AccessToken;

class RefreshTokenMapper
{
    public function fromDtoToResponse(AccessToken $accessToken): RefreshTokenResponse
    {
        return new RefreshTokenResponse(
            data: new RefreshTokenResponseData(
                accessToken: $accessToken->getAccessToken(),
                refreshToken: $accessToken->getRefreshToken(),
            ),
        );
    }
}
