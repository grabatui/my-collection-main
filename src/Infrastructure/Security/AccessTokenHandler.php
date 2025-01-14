<?php

declare(strict_types=1);

namespace App\Infrastructure\Security;

use App\Domain\Repository\AccessTokenRepository;
use App\Domain\Service\AccessToken\TokenValidator;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

readonly class AccessTokenHandler implements AccessTokenHandlerInterface
{
    public function __construct(
        private AccessTokenRepository $accessTokenRepository,
        private TokenValidator $tokenValidator,
    ) {
    }

    public function getUserBadgeFrom(string $accessToken): UserBadge
    {
        if (!$this->tokenValidator->validate($accessToken)) {
            throw new BadCredentialsException('Неверный токен авторизации');
        }

        $accessToken = $this->accessTokenRepository->findOneByAccessToken($accessToken);

        if (!$accessToken) {
            throw new BadCredentialsException('Ошибка в авторизационных данных');
        }

        return new UserBadge(
            $accessToken->getUser()->getUserIdentifier()
        );
    }
}
