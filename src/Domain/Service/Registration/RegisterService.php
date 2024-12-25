<?php

declare(strict_types=1);

namespace App\Domain\Service\Registration;

use App\Domain\Entity\User;
use App\Domain\Repository\UserRepository;
use App\Domain\Service\Password\PasswordHasher;
use App\Domain\Service\Registration\Dto\RegisterDto;
use Symfony\Component\Uid\Factory\UuidFactory;

readonly class RegisterService
{
    private const string DEFAULT_ROLE = 'ROLE_USER';

    public function __construct(
        private UserRepository $userRepository,
        private UuidFactory $uuidFactory,
        private PasswordHasher $passwordHasher,
    ) {
    }

    public function execute(RegisterDto $dto): User
    {
        $user = User::create(
            id: $this->uuidFactory->create(),
            email: $dto->email,
            name: $dto->name,
            password: $this->passwordHasher->execute($dto->password),
            roles: [self::DEFAULT_ROLE],
        );

        $this->userRepository->save($user);

        return $user;
    }
}
