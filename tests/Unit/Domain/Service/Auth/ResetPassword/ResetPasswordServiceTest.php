<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Service\Auth\ResetPassword;

use App\Domain\Exception\Auth\ResetPasswordException;
use App\Domain\Repository\UserRepository;
use App\Domain\Service\Auth\ResetPassword\ResetPasswordService;
use App\Domain\Service\Password\PasswordHasher;
use App\Tests\Core\Trait\Entity\UserTrait;
use App\Tests\Core\UnitTestSuit;
use SymfonyCasts\Bundle\ResetPassword\Exception\InvalidResetPasswordTokenException;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

/**
 * @covers \App\Domain\Service\Auth\ResetPassword\ResetPasswordService
 */
class ResetPasswordServiceTest extends UnitTestSuit
{
    use UserTrait;

    /**
     * @covers \App\Domain\Service\Auth\ResetPassword\ResetPasswordService::execute
     */
    public function testHappyPathExecute(): void
    {
        $token = $this->getFakeWord();
        $password = $this->faker->password();

        $hashedPassword = $this->faker->sha256();
        $user = $this->makeUserEntity();

        $resetPasswordHelper = $this->createMock(ResetPasswordHelperInterface::class);
        $resetPasswordHelper
            ->expects($this->once())
            ->method('validateTokenAndFetchUser')
            ->with($token)
            ->willReturn($user);
        $passwordHasher = $this->createMock(PasswordHasher::class);
        $passwordHasher
            ->expects($this->once())
            ->method('execute')
            ->with($password)
            ->willReturn($hashedPassword);
        $userRepository = $this->createMock(UserRepository::class);
        $userRepository
            ->expects($this->once())
            ->method('save')
            ->with($user);

        $service = new ResetPasswordService(
            $resetPasswordHelper,
            $passwordHasher,
            $userRepository,
        );

        $service->execute($token, $password);

        $this->assertEquals($hashedPassword, $user->getPassword());
    }

    /**
     * @covers \App\Domain\Service\Auth\ResetPassword\ResetPasswordService::execute
     */
    public function testExceptionResetPassword(): void
    {
        $token = $this->getFakeWord();
        $password = $this->faker->password();

        $resetPasswordHelper = $this->createMock(ResetPasswordHelperInterface::class);
        $resetPasswordHelper
            ->expects($this->once())
            ->method('validateTokenAndFetchUser')
            ->with($token)
            ->willThrowException(new InvalidResetPasswordTokenException());
        $passwordHasher = $this->createMock(PasswordHasher::class);
        $passwordHasher
            ->expects($this->never())
            ->method('execute');
        $userRepository = $this->createMock(UserRepository::class);
        $userRepository
            ->expects($this->never())
            ->method('save');

        $service = new ResetPasswordService(
            $resetPasswordHelper,
            $passwordHasher,
            $userRepository,
        );

        $this->expectException(ResetPasswordException::class);

        $service->execute($token, $password);
    }
}
