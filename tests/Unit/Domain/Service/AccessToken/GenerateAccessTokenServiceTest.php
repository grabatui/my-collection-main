<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Service\AccessToken;

use App\Domain\Entity\AccessToken;
use App\Domain\Repository\AccessTokenRepository;
use App\Domain\Service\AccessToken\GenerateAccessTokenService;
use App\Domain\Service\AccessToken\TokenFactory;
use App\Tests\Core\Trait\Entity\UserTrait;
use App\Tests\Core\UnitTestSuit;

/**
 * @covers \App\Domain\Service\AccessToken\GenerateAccessTokenService
 */
class GenerateAccessTokenServiceTest extends UnitTestSuit
{
    use UserTrait;

    /**
     * @covers \App\Domain\Service\AccessToken\GenerateAccessTokenService::execute
     */
    public function testHappyPathExecute(): void
    {
        $user = $this->makeUserEntity();

        $accessToken = $this->faker->sha256();
        $refreshToken = $this->faker->sha256();

        $accessTokenRepository = $this->createMock(AccessTokenRepository::class);
        $accessTokenRepository
            ->expects($this->once())
            ->method('save')
            ->with(
                self::callback(function (AccessToken $actualAccessToken) use ($user, $accessToken, $refreshToken): bool {
                    $this->assertEquals($accessToken, $actualAccessToken->getAccessToken());
                    $this->assertEquals($refreshToken, $actualAccessToken->getRefreshToken());
                    $this->assertEquals($user, $actualAccessToken->getUser());

                    return true;
                })
            );

        $tokenFactory = $this->createMock(TokenFactory::class);
        $tokenFactory
            ->expects($this->once())
            ->method('generateAccessToken')
            ->with($user)
            ->willReturn($accessToken);
        $tokenFactory
            ->expects($this->once())
            ->method('generateRefreshToken')
            ->with($user)
            ->willReturn($refreshToken);

        $service = new GenerateAccessTokenService(
            $accessTokenRepository,
            $tokenFactory,
        );

        $actualResult = $service->execute($user);

        $this->assertEquals($accessToken, $actualResult->getAccessToken());
        $this->assertEquals($refreshToken, $actualResult->getRefreshToken());
        $this->assertEquals($user, $actualResult->getUser());
    }
}
