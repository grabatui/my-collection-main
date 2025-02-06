<?php

declare(strict_types=1);

namespace App\Tests\Core\Trait\Entity;

use App\Domain\Entity\AccessToken;
use App\Domain\Entity\User;

trait AccessTokenTrait
{
    use UserTrait;

    public function makeAccessTokenEntity(
        ?User $user = null,
    ): AccessToken {
        return AccessToken::create(
            user: $user ?? $this->findOrCreateUserToDatabase(),
            accessToken: $this->faker->md5(),
            refreshToken: $this->faker->md5(),
        );
    }

    public function createAccessTokenToDatabase(
        ?User $user = null,
    ): AccessToken {
        $accessToken = $this->makeAccessTokenEntity($user);

        $this->getEntityManager()->persist($accessToken);
        $this->getEntityManager()->flush();

        return $accessToken;
    }
}
