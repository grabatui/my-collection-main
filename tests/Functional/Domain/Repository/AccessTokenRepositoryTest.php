<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\Repository;

use App\Domain\Repository\AccessTokenRepository;
use App\Tests\Core\KernelTestSuit;
use App\Tests\Core\Trait\Entity\AccessTokenTrait;
use App\Tests\Core\Trait\Entity\UserTrait;

/**
 * @covers \App\Domain\Repository\AccessTokenRepository
 */
class AccessTokenRepositoryTest extends KernelTestSuit
{
    use UserTrait;
    use AccessTokenTrait;

    /**
     * @covers \App\Domain\Repository\AccessTokenRepository::findOneByAccessToken
     */
    public function testHappyPathFindOneByAccessToken(): void
    {
        $accessToken = $this->createAccessTokenToDatabase();

        $repository = $this->getAccessTokenRepository();

        // Exists
        $actualAccessToken = $repository->findOneByAccessToken(
            $accessToken->getAccessToken(),
        );

        $this->assertEntitiesEqual($accessToken, $actualAccessToken);

        // Not found
        $this->assertNull(
            $repository->findOneByAccessToken(
                $this->faker->md5(),
            )
        );
    }

    /**
     * @covers \App\Domain\Repository\AccessTokenRepository::findOneByRefreshToken
     */
    public function testHappyPathFindOneByRefreshToken(): void
    {
        $accessToken = $this->createAccessTokenToDatabase();

        $repository = $this->getAccessTokenRepository();

        // Exists
        $actualAccessToken = $repository->findOneByRefreshToken(
            $accessToken->getRefreshToken(),
        );

        $this->assertEntitiesEqual($accessToken, $actualAccessToken);

        // Not found
        $this->assertNull(
            $repository->findOneByRefreshToken(
                $this->faker->md5(),
            )
        );
    }

    /**
     * @covers \App\Domain\Repository\AccessTokenRepository::save
     */
    public function testHappyPathSave(): void
    {
        $accessToken = $this->makeAccessTokenEntity();

        $repository = $this->getAccessTokenRepository();

        $this->assertNull(
            $repository->findOneByAccessToken(
                $accessToken->getAccessToken(),
            ),
        );

        $repository->save($accessToken);

        $this->assertNotNull(
            $repository->findOneByAccessToken(
                $accessToken->getAccessToken(),
            ),
        );
    }

    /**
     * @covers \App\Domain\Repository\AccessTokenRepository::deleteAllByUser
     */
    public function testHappyPathDeleteAllByUser(): void
    {
        $user = $this->findOrCreateUserToDatabase();

        $this->createAccessTokenToDatabase($user);
        $this->createAccessTokenToDatabase($user);

        $repository = $this->getAccessTokenRepository();

        $actualAccessTokens = $repository->findBy([
            'user' => $user,
            'deletedAt' => null,
        ]);
        $this->assertCount(2, $actualAccessTokens);

        $repository->deleteAllByUser($user);

        $actualAccessTokens = $repository->findBy([
            'user' => $user,
            'deletedAt' => null,
        ]);
        $this->assertEmpty($actualAccessTokens);
    }

    private function getAccessTokenRepository(): AccessTokenRepository
    {
        /** @var AccessTokenRepository $repository */
        $repository = $this->getService(AccessTokenRepository::class);

        return $repository;
    }
}
