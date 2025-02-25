<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\Repository;

use App\Domain\Entity\ResetPasswordRequest;
use App\Domain\Entity\User;
use App\Domain\Repository\ResetPasswordRequestRepository;
use App\Tests\Core\KernelTestSuit;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

/**
 * @covers \App\Domain\Repository\ResetPasswordRequestRepository
 */
class ResetPasswordRequestRepositoryTest extends KernelTestSuit
{
    /**
     * @covers \App\Domain\Repository\ResetPasswordRequestRepository::createResetPasswordRequest
     */
    public function testHappyPathCreateResetPasswordRequest(): void
    {
        $user = $this->createMock(User::class);
        $expiredAt = new \DateTimeImmutable();
        $selector = $this->getFakeWord();
        $hashedToken = $this->faker->sha256();

        $repository = $this->getResetPasswordRequestRepository();

        $actualEntity = $repository->createResetPasswordRequest(
            user: $user,
            expiresAt: $expiredAt,
            selector: $selector,
            hashedToken: $hashedToken,
        );

        $this->assertEntitiesEqual(
            ResetPasswordRequest::create(
                user: $user,
                expiresAt: $expiredAt,
                selector: $selector,
                hashedToken: $hashedToken,
            ),
            $actualEntity
        );
    }

    /**
     * @covers \App\Domain\Repository\ResetPasswordRequestRepository::createResetPasswordRequest
     */
    public function testExceptionCreateResetPasswordRequestWrongUserClass(): void
    {
        $user = new \stdClass();

        $repository = $this->getResetPasswordRequestRepository();

        $this->expectException(UnsupportedUserException::class);

        $repository->createResetPasswordRequest(
            user: $user,
            expiresAt: new \DateTimeImmutable(),
            selector: $this->getFakeWord(),
            hashedToken: $this->faker->sha256(),
        );
    }

    private function getResetPasswordRequestRepository(): ResetPasswordRequestRepository
    {
        /** @var ResetPasswordRequestRepository $repository */
        $repository = $this->getService(ResetPasswordRequestRepository::class);

        return $repository;
    }
}
