<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Service\Auth\RefreshToken;

use App\Domain\Exception\Auth\TokenIsInvalidException;
use App\Domain\Repository\AccessTokenRepository;
use App\Domain\Service\AccessToken\TokenValidator;
use App\Domain\Service\Auth\AuthRateLimiter;
use App\Domain\Service\Auth\RefreshToken\RefreshTokenService;
use App\Tests\Core\Trait\Entity\AccessTokenTrait;
use App\Tests\Core\Trait\Entity\UserTrait;
use App\Tests\Core\UnitTestSuit;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

/**
 * @covers \App\Domain\Service\Auth\RefreshToken\RefreshTokenService
 */
class RefreshTokenServiceTest extends UnitTestSuit
{
    use UserTrait;
    use AccessTokenTrait;

    /**
     * @covers \App\Domain\Service\Auth\RefreshToken\RefreshTokenService::execute
     */
    public function testHappyPathExecute(): void
    {
        $user = $this->makeUserEntity();
        $accessToken = $this->makeAccessTokenEntity($user);

        $refreshToken = $this->faker->sha256();
        $clientIp = $this->faker->ipv4();

        $accessTokenRepository = $this->createMock(AccessTokenRepository::class);
        $accessTokenRepository
            ->expects($this->once())
            ->method('findOneByRefreshToken')
            ->with($refreshToken)
            ->willReturn($accessToken);
        $accessTokenRepository
            ->expects($this->once())
            ->method('deleteAllByUser')
            ->with($user);
        $authRateLimiter = $this->createMock(AuthRateLimiter::class);
        $authRateLimiter
            ->expects($this->once())
            ->method('acceptByClientIp')
            ->with($clientIp);
        $tokenValidator = $this->createMock(TokenValidator::class);
        $tokenValidator
            ->expects($this->once())
            ->method('validate')
            ->with($refreshToken)
            ->willReturn(true);

        $service = new RefreshTokenService(
            $accessTokenRepository,
            $authRateLimiter,
            $tokenValidator,
        );

        $service->execute($refreshToken, $clientIp);
    }

    /**
     * @covers \App\Domain\Service\Auth\RefreshToken\RefreshTokenService::execute
     */
    public function testExceptionTokenInvalid(): void
    {
        $refreshToken = $this->faker->sha256();
        $clientIp = $this->faker->ipv4();

        $accessTokenRepository = $this->createMock(AccessTokenRepository::class);
        $accessTokenRepository
            ->expects($this->never())
            ->method('findOneByRefreshToken');
        $accessTokenRepository
            ->expects($this->never())
            ->method('deleteAllByUser');
        $authRateLimiter = $this->createMock(AuthRateLimiter::class);
        $authRateLimiter
            ->expects($this->never())
            ->method('acceptByClientIp');
        $tokenValidator = $this->createMock(TokenValidator::class);
        $tokenValidator
            ->expects($this->once())
            ->method('validate')
            ->with($refreshToken)
            ->willReturn(false);

        $service = new RefreshTokenService(
            $accessTokenRepository,
            $authRateLimiter,
            $tokenValidator,
        );

        $this->expectException(TokenIsInvalidException::class);

        $service->execute($refreshToken, $clientIp);
    }

    /**
     * @covers \App\Domain\Service\Auth\RefreshToken\RefreshTokenService::execute
     */
    public function testExceptionWithoutAccessToken(): void
    {
        $user = $this->makeUserEntity();

        $refreshToken = $this->faker->sha256();
        $clientIp = $this->faker->ipv4();

        $accessTokenRepository = $this->createMock(AccessTokenRepository::class);
        $accessTokenRepository
            ->expects($this->once())
            ->method('findOneByRefreshToken')
            ->with($refreshToken)
            ->willReturn(null);
        $accessTokenRepository
            ->expects($this->never())
            ->method('deleteAllByUser');
        $authRateLimiter = $this->createMock(AuthRateLimiter::class);
        $authRateLimiter
            ->expects($this->never())
            ->method('acceptByClientIp');
        $tokenValidator = $this->createMock(TokenValidator::class);
        $tokenValidator
            ->expects($this->once())
            ->method('validate')
            ->with($refreshToken)
            ->willReturn(true);

        $service = new RefreshTokenService(
            $accessTokenRepository,
            $authRateLimiter,
            $tokenValidator,
        );

        $this->expectException(TokenIsInvalidException::class);

        $service->execute($refreshToken, $clientIp);
    }

    /**
     * @covers \App\Domain\Service\Auth\RefreshToken\RefreshTokenService::execute
     */
    public function testExceptionExecuteClientIpRateLimitExceeded(): void
    {
        $user = $this->makeUserEntity();
        $accessToken = $this->makeAccessTokenEntity($user);

        $refreshToken = $this->faker->sha256();
        $clientIp = $this->faker->ipv4();

        $accessTokenRepository = $this->createMock(AccessTokenRepository::class);
        $accessTokenRepository
            ->expects($this->once())
            ->method('findOneByRefreshToken')
            ->with($refreshToken)
            ->willReturn($accessToken);
        $accessTokenRepository
            ->expects($this->never())
            ->method('deleteAllByUser');
        $authRateLimiter = $this->createMock(AuthRateLimiter::class);
        $authRateLimiter
            ->expects($this->once())
            ->method('acceptByClientIp')
            ->with($clientIp)
            ->willThrowException(new TooManyRequestsHttpException());
        $tokenValidator = $this->createMock(TokenValidator::class);
        $tokenValidator
            ->expects($this->once())
            ->method('validate')
            ->with($refreshToken)
            ->willReturn(true);

        $service = new RefreshTokenService(
            $accessTokenRepository,
            $authRateLimiter,
            $tokenValidator,
        );

        $this->expectException(TooManyRequestsHttpException::class);

        $service->execute($refreshToken, $clientIp);
    }
}
