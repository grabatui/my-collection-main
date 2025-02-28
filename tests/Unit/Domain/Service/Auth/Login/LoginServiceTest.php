<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Service\Auth\Login;

use App\Domain\Exception\Auth\LoginOrPasswordIsIncorrect;
use App\Domain\Repository\AccessTokenRepository;
use App\Domain\Repository\UserRepository;
use App\Domain\Service\Auth\AuthRateLimiter;
use App\Domain\Service\Auth\Login\Dto\LoginDto;
use App\Domain\Service\Auth\Login\LoginService;
use App\Domain\Service\Password\PasswordHasher;
use App\Tests\Core\Trait\Entity\UserTrait;
use App\Tests\Core\UnitTestSuit;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

/**
 * @covers \App\Domain\Service\Auth\Login\LoginService
 */
class LoginServiceTest extends UnitTestSuit
{
    use UserTrait;

    /**
     * @covers \App\Domain\Service\Auth\Login\LoginService::execute
     */
    public function testHappyPathExecute(): void
    {
        $loginDto = new LoginDto(
            email: $this->faker->email(),
            password: $this->faker->password(),
        );
        $clientIp = $this->faker->ipv4();

        $user = $this->makeUserEntity();

        $userRepository = $this->createMock(UserRepository::class);
        $userRepository
            ->expects($this->once())
            ->method('findByEmail')
            ->with($loginDto->email)
            ->willReturn($user);
        $accessTokenRepository = $this->createMock(AccessTokenRepository::class);
        $accessTokenRepository
            ->expects($this->once())
            ->method('deleteAllByUser')
            ->with($user);
        $passwordHasher = $this->createMock(PasswordHasher::class);
        $passwordHasher
            ->expects($this->once())
            ->method('verify')
            ->with($loginDto->password, $user->getPassword())
            ->willReturn(true);
        $authRateLimiter = $this->createMock(AuthRateLimiter::class);
        $authRateLimiter
            ->expects($this->once())
            ->method('acceptByClientIp')
            ->with($clientIp);

        $service = new LoginService(
            $userRepository,
            $accessTokenRepository,
            $passwordHasher,
            $authRateLimiter,
        );

        $service->execute(
            dto: $loginDto,
            clientIp: $clientIp,
        );
    }

    /**
     * @covers \App\Domain\Service\Auth\Login\LoginService::execute
     */
    public function testExceptionExecuteUserNotFound(): void
    {
        $loginDto = new LoginDto(
            email: $this->faker->email(),
            password: $this->faker->password(),
        );
        $clientIp = $this->faker->ipv4();

        $userRepository = $this->createMock(UserRepository::class);
        $userRepository
            ->expects($this->once())
            ->method('findByEmail')
            ->with($loginDto->email)
            ->willReturn(null);
        $accessTokenRepository = $this->createMock(AccessTokenRepository::class);
        $accessTokenRepository
            ->expects($this->never())
            ->method('deleteAllByUser');
        $passwordHasher = $this->createMock(PasswordHasher::class);
        $passwordHasher
            ->expects($this->never())
            ->method('verify');
        $authRateLimiter = $this->createMock(AuthRateLimiter::class);
        $authRateLimiter
            ->expects($this->never())
            ->method('acceptByClientIp');

        $service = new LoginService(
            $userRepository,
            $accessTokenRepository,
            $passwordHasher,
            $authRateLimiter,
        );

        $this->expectException(LoginOrPasswordIsIncorrect::class);

        $service->execute(
            dto: $loginDto,
            clientIp: $clientIp,
        );
    }

    /**
     * @covers \App\Domain\Service\Auth\Login\LoginService::execute
     */
    public function testExceptionExecuteWrongPassword(): void
    {
        $loginDto = new LoginDto(
            email: $this->faker->email(),
            password: $this->faker->password(),
        );
        $clientIp = $this->faker->ipv4();

        $user = $this->makeUserEntity();

        $userRepository = $this->createMock(UserRepository::class);
        $userRepository
            ->expects($this->once())
            ->method('findByEmail')
            ->with($loginDto->email)
            ->willReturn($user);
        $accessTokenRepository = $this->createMock(AccessTokenRepository::class);
        $accessTokenRepository
            ->expects($this->never())
            ->method('deleteAllByUser');
        $passwordHasher = $this->createMock(PasswordHasher::class);
        $passwordHasher
            ->expects($this->once())
            ->method('verify')
            ->with($loginDto->password, $user->getPassword())
            ->willReturn(false);
        $authRateLimiter = $this->createMock(AuthRateLimiter::class);
        $authRateLimiter
            ->expects($this->never())
            ->method('acceptByClientIp');

        $service = new LoginService(
            $userRepository,
            $accessTokenRepository,
            $passwordHasher,
            $authRateLimiter,
        );

        $this->expectException(LoginOrPasswordIsIncorrect::class);

        $service->execute(
            dto: $loginDto,
            clientIp: $clientIp,
        );
    }

    /**
     * @covers \App\Domain\Service\Auth\Login\LoginService::execute
     */
    public function testExceptionExecuteClientIpRateLimitExceeded(): void
    {
        $loginDto = new LoginDto(
            email: $this->faker->email(),
            password: $this->faker->password(),
        );
        $clientIp = $this->faker->ipv4();

        $user = $this->makeUserEntity();

        $userRepository = $this->createMock(UserRepository::class);
        $userRepository
            ->expects($this->once())
            ->method('findByEmail')
            ->with($loginDto->email)
            ->willReturn($user);
        $accessTokenRepository = $this->createMock(AccessTokenRepository::class);
        $accessTokenRepository
            ->expects($this->never())
            ->method('deleteAllByUser');
        $passwordHasher = $this->createMock(PasswordHasher::class);
        $passwordHasher
            ->expects($this->once())
            ->method('verify')
            ->with($loginDto->password, $user->getPassword())
            ->willReturn(true);
        $authRateLimiter = $this->createMock(AuthRateLimiter::class);
        $authRateLimiter
            ->expects($this->once())
            ->method('acceptByClientIp')
            ->with($clientIp)
            ->willThrowException(new TooManyRequestsHttpException());

        $service = new LoginService(
            $userRepository,
            $accessTokenRepository,
            $passwordHasher,
            $authRateLimiter,
        );

        $this->expectException(TooManyRequestsHttpException::class);

        $service->execute(
            dto: $loginDto,
            clientIp: $clientIp,
        );
    }
}
