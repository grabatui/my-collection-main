<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Service\Auth\Logout;

use App\Domain\Repository\AccessTokenRepository;
use App\Domain\Service\Auth\Logout\LogoutService;
use App\Tests\Core\Trait\Entity\UserTrait;
use App\Tests\Core\UnitTestSuit;

/**
 * @covers \App\Domain\Service\Auth\Logout\LogoutService
 */
class LogoutServiceTest extends UnitTestSuit
{
    use UserTrait;

    /**
     * @covers \App\Domain\Service\Auth\Logout\LogoutService::execute
     */
    public function testHappyPathExecute(): void
    {
        $user = $this->makeUserEntity();

        $accessTokenRepository = $this->createMock(AccessTokenRepository::class);
        $accessTokenRepository
            ->expects($this->once())
            ->method('deleteAllByUser')
            ->with($user);

        $service = new LogoutService(
            $accessTokenRepository,
        );

        $service->execute($user);
    }
}
