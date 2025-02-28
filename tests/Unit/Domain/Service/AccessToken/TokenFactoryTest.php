<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Service\AccessToken;

use App\Domain\Service\AccessToken\TokenFactory;
use App\Tests\Core\Trait\Entity\UserTrait;
use App\Tests\Core\UnitTestSuit;
use ReallySimpleJWT\Token;

/**
 * @covers \App\Domain\Service\AccessToken\TokenFactory
 */
class TokenFactoryTest extends UnitTestSuit
{
    use UserTrait;

    /**
     * @covers \App\Domain\Service\AccessToken\TokenFactory::generateAccessToken
     */
    public function testHappyPathGenerateAccessToken(): void
    {
        $secret = 'someTestStr1ng12!';
        $tokenTtl = $this->getFakeNumber();

        $user = $this->makeUserEntity();

        $factory = new TokenFactory(
            secret: $secret,
            tokenTtl: $tokenTtl,
        );

        $actualAccessToken = $factory->generateAccessToken($user);

        $this->assertEquals(
            Token::create(
                userId: $user->getUserIdentifier(),
                secret: $secret,
                expiration: time() + $tokenTtl,
                issuer: 'localhost',
            ),
            $actualAccessToken
        );
    }

    /**
     * @covers \App\Domain\Service\AccessToken\TokenFactory::generateRefreshToken
     */
    public function testHappyPathGenerateRefreshToken(): void
    {
        $secret = 'someTestStr1ng12!';
        $tokenTtl = $this->getFakeNumber();

        $user = $this->makeUserEntity();

        $factory = new TokenFactory(
            secret: $secret,
            tokenTtl: $tokenTtl,
        );

        $actualAccessToken = $factory->generateRefreshToken($user);

        $this->assertEquals(
            Token::create(
                userId: $user->getUserIdentifier(),
                secret: $secret,
                expiration: time() + $tokenTtl + 3600,
                issuer: 'localhost',
            ),
            $actualAccessToken
        );
    }
}
