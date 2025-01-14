<?php

declare(strict_types=1);

namespace App\Domain\Service\AccessToken;

use App\Domain\Entity\AccessToken;
use App\Domain\Entity\User;
use App\Domain\Repository\AccessTokenRepository;

class GenerateAccessTokenService
{
    public function __construct(
        private readonly AccessTokenRepository $accessTokenRepository,
        private readonly TokenFactory $tokenFactory,
    ) {
    }

    public function execute(User $user): AccessToken
    {
        $accessToken = AccessToken::create(
            user: $user,
            accessToken: $this->tokenFactory->generateAccessToken($user),
            refreshToken: $this->tokenFactory->generateRefreshToken($user),
        );

        $this->accessTokenRepository->save($accessToken);

        return $accessToken;
    }
}
