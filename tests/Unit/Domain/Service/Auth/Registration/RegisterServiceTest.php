<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Service\Auth\Registration;

use App\Domain\Entity\User;
use App\Domain\Repository\UserRepository;
use App\Domain\Service\Auth\Registration\Dto\RegisterDto;
use App\Domain\Service\Auth\Registration\RegisterService;
use App\Domain\Service\Password\PasswordHasher;
use App\Tests\Core\UnitTestSuit;
use Symfony\Component\Uid\Factory\UuidFactory;
use Symfony\Component\Uid\Uuid;

/**
 * @covers \App\Domain\Service\Auth\Registration\RegisterService
 */
class RegisterServiceTest extends UnitTestSuit
{
    /**
     * @covers \App\Domain\Service\Auth\Registration\RegisterService
     */
    public function testHappyPath(): void
    {
        $registerDto = new RegisterDto(
            email: $this->faker->email(),
            name: $this->getFakeWord(),
            password: $this->faker->password(),
        );

        $uuid = Uuid::v7();
        $hashedPassword = $this->faker->sha256();

        $userRepository = $this->createMock(UserRepository::class);
        $userRepository
            ->expects($this->once())
            ->method('save')
            ->with(
                self::callback(function (User $user) use ($registerDto, $uuid, $hashedPassword): bool {
                    $this->assertEquals($registerDto->email, $user->getEmail());
                    $this->assertEquals($registerDto->name, $user->getName());
                    $this->assertEquals($uuid, $user->getId());
                    $this->assertEquals($hashedPassword, $user->getPassword());

                    return true;
                })
            );
        $uuidFactory = $this->createMock(UuidFactory::class);
        $uuidFactory
            ->expects($this->once())
            ->method('create')
            ->willReturn($uuid);
        $passwordHasher = $this->createMock(PasswordHasher::class);
        $passwordHasher
            ->expects($this->once())
            ->method('execute')
            ->with($registerDto->password)
            ->willReturn($hashedPassword);

        $service = new RegisterService(
            $userRepository,
            $uuidFactory,
            $passwordHasher,
        );

        $service->execute($registerDto);
    }
}
