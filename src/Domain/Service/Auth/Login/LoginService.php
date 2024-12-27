<?php

declare(strict_types=1);

namespace App\Domain\Service\Auth\Login;

use App\Domain\Entity\User;
use App\Domain\Exception\Auth\PasswordIsIncorrect;
use App\Domain\Exception\User\UserNotFoundException;
use App\Domain\Repository\AccessTokenRepository;
use App\Domain\Repository\UserRepository;
use App\Domain\Service\Auth\Login\Dto\LoginDto;
use App\Domain\Service\Password\PasswordHasher;

readonly class LoginService
{
    public function __construct(
        private UserRepository $userRepository,
        private AccessTokenRepository $accessTokenRepository,
        private PasswordHasher $passwordHasher,
        private LoginRateLimiter $loginRateLimiter,
    ) {
    }

    public function execute(
        LoginDto $dto,
        ?string $clientIp,
    ): User {
        $user = $this->userRepository->findByEmail($dto->email);

        if (!$user) {
            throw new UserNotFoundException();
        }

        $hashedPassword = $this->passwordHasher->execute($dto->password);

        if (password_verify($dto->password, $hashedPassword)) {
            throw new PasswordIsIncorrect();
        }

        if ($clientIp) {
            $this->loginRateLimiter->acceptByClientIp($clientIp);
        }

        $this->accessTokenRepository->deleteAllByUser($user);

        return $user;
    }
}
