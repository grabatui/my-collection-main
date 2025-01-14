<?php

declare(strict_types=1);

namespace App\Domain\Service\Auth\RefreshToken;

use App\Domain\Entity\User;
use App\Domain\Exception\Auth\TokenIsInvalidException;
use App\Domain\Repository\AccessTokenRepository;
use App\Domain\Service\AccessToken\TokenValidator;
use App\Domain\Service\Auth\AuthRateLimiter;

readonly class RefreshTokenService
{
    public function __construct(
        private AccessTokenRepository $accessTokenRepository,
        private AuthRateLimiter $authRateLimiter,
        private TokenValidator $tokenValidator,
    ) {}

    public function execute(
        string $refreshToken,
        ?string $clientIp,
    ): User {
        if (!$this->tokenValidator->validate($refreshToken)) {
            throw new TokenIsInvalidException();
        }

        $accessToken = $this->accessTokenRepository->findOneByRefreshToken($refreshToken);

        if (!$accessToken) {
            throw new TokenIsInvalidException();
        }

        if ($clientIp) {
            $this->authRateLimiter->acceptByClientIp($clientIp);
        }

        $this->accessTokenRepository->deleteAllByUser(
            $accessToken->getUser(),
        );

        return $accessToken->getUser();
    }
}
