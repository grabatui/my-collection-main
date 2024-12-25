<?php

declare(strict_types=1);

namespace App\Infrastructure\Security;

use App\Domain\Repository\AccessTokenRepository;
use ReallySimpleJWT\Token;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

readonly class AccessTokenHandler implements AccessTokenHandlerInterface
{
    public function __construct(
        private AccessTokenRepository $accessTokenRepository,
        private string $secret,
    ) {
    }

    public function getUserBadgeFrom(string $accessToken): UserBadge
    {
        if (!$this->isAccessTokenValid($accessToken)) {
            throw new BadCredentialsException('Неверный токен авторизации');
        }

        $accessToken = $this->accessTokenRepository->findOneByToken($accessToken);

        if (!$accessToken) {
            throw new BadCredentialsException('Ошибка в авторизационных данных');
        }

        return new UserBadge(
            $accessToken->getUser()->getUserIdentifier()
        );
    }

    private function isAccessTokenValid(string $accessToken): bool
    {
        return Token::validate($accessToken, $this->secret) && Token::validateExpiration($accessToken);
    }
}
