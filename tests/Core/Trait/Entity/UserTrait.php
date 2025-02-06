<?php

declare(strict_types=1);

namespace App\Tests\Core\Trait\Entity;

use App\Domain\Entity\User;
use App\Domain\Repository\UserRepository;
use Symfony\Component\Uid\Uuid;

trait UserTrait
{
    public function makeUserEntity(
        ?string $email = null,
    ): User {
        return User::create(
            id: new Uuid($this->faker->uuid()),
            email: $email ?: $this->faker->email(),
            name: $this->faker->name(),
            password: $this->faker->password(),
            roles: ['ROLE_USER'],
        );
    }

    public function findOrCreateUserToDatabase(
        ?string $email = null,
    ): User {
        $email ??= $this->faker->email();

        $user = $this->getService(UserRepository::class)->findOneBy(['email' => $email]);

        if ($user) {
            return $user;
        }

        $user = $this->makeUserEntity($email);

        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();

        return $user;
    }
}
