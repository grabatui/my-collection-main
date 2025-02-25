<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\Repository;

use App\Domain\Repository\UserRepository;
use App\Tests\Core\KernelTestSuit;
use App\Tests\Core\Trait\Entity\UserTrait;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\InMemoryUser;

/**
 * @covers \App\Domain\Repository\UserRepository
 */
class UserRepositoryTest extends KernelTestSuit
{
    use UserTrait;

    private const string CUSTOM_EMAIL = 'custom@email.com';

    protected function tearDown(): void
    {
        parent::tearDown();

        $user = $this->getUserRepository()->findByEmail(self::CUSTOM_EMAIL);

        if ($user) {
            $this->getEntityManager()->remove($user);
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @covers \App\Domain\Repository\UserRepository::findByEmail
     */
    public function testHappyPathFindByEmail(): void
    {
        $email = $this->faker->email();

        $user = $this->findOrCreateUserToDatabase($email);

        $repository = $this->getUserRepository();

        $this->assertEntitiesEqual(
            $user,
            $repository->findByEmail($email),
        );

        $this->assertNull(
            $repository->findByEmail('wrongEmail'),
        );
    }

    /**
     * @covers \App\Domain\Repository\UserRepository::upgradePassword
     */
    public function testHappyPathUpgradePassword(): void
    {
        $user = $this->findOrCreateUserToDatabase();

        $newHashedPassword = $this->faker->password();

        $repository = $this->getUserRepository();

        $repository->upgradePassword($user, $newHashedPassword);

        $user = $repository->findByEmail($user->getEmail());

        $this->assertEquals($newHashedPassword, $user->getPassword());
    }

    /**
     * @covers \App\Domain\Repository\UserRepository::upgradePassword
     */
    public function testExceptionUpgradePasswordWrongUserEntity(): void
    {
        $user = new InMemoryUser(
            username: $this->getFakeWord(),
            password: $this->faker->password(),
        );

        $newHashedPassword = $this->faker->password();

        $repository = $this->getUserRepository();

        $this->expectException(UnsupportedUserException::class);

        $repository->upgradePassword($user, $newHashedPassword);
    }

    /**
     * @covers \App\Domain\Repository\UserRepository::save
     */
    public function testHappyPathSave(): void
    {
        $user = $this->makeUserEntity(self::CUSTOM_EMAIL);

        $repository = $this->getUserRepository();

        $this->assertNull(
            $repository->findByEmail(self::CUSTOM_EMAIL),
        );

        $repository->save($user);

        $this->assertNotNull(
            $repository->findByEmail(self::CUSTOM_EMAIL),
        );
    }

    private function getUserRepository(): UserRepository
    {
        /** @var UserRepository $repository */
        $repository = $this->getService(UserRepository::class);

        return $repository;
    }
}
