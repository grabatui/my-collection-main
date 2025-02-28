<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Service\AccessToken;

use App\Domain\Service\AccessToken\TokenValidator;
use App\Tests\Core\Trait\Entity\UserTrait;
use App\Tests\Core\UnitTestSuit;
use ReallySimpleJWT\Token;

/**
 * @covers \App\Domain\Service\AccessToken\TokenValidator
 */
class TokenValidatorTest extends UnitTestSuit
{
    use UserTrait;

    /**
     * @covers \App\Domain\Service\AccessToken\TokenValidator::validate
     */
    public function testHappyPathValidate(): void
    {
        $secret = 'someTestStr1ng12!';

        $user = $this->makeUserEntity();

        $validator = new TokenValidator(
            secret: $secret,
        );

        $this->assertTrue(
            $validator->validate(
                Token::create(
                    userId: $user->getUserIdentifier(),
                    secret: $secret,
                    expiration: time() + 3600,
                    issuer: 'localhost',
                ),
            ),
        );

        $this->assertFalse(
            $validator->validate(
                Token::create(
                    userId: $user->getUserIdentifier(),
                    secret: $secret . 'wrong',
                    expiration: time() + 3600,
                    issuer: 'localhost',
                ),
            ),
        );
    }
}
