<?php

declare(strict_types=1);

namespace App\Domain\Service\Auth\Login;

use App\Domain\Entity\User;
use App\Domain\Exception\Auth\LoginOrPasswordIsIncorrect;
use App\Domain\Repository\AccessTokenRepository;
use App\Domain\Repository\UserRepository;
use App\Domain\Service\Auth\Login\Dto\LoginDto;
use App\Domain\Service\Auth\AuthRateLimiter;
use App\Domain\Service\Password\PasswordHasher;

readonly class LoginService
{
    public function __construct(
        private UserRepository $userRepository,
        private AccessTokenRepository $accessTokenRepository,
        private PasswordHasher $passwordHasher,
        private AuthRateLimiter $authRateLimiter,
    ) {
    }

    public function execute(
        LoginDto $dto,
        ?string $clientIp,
    ): User {
        $user = $this->userRepository->findByEmail($dto->email);

        if (!$user) {
            throw new LoginOrPasswordIsIncorrect();
        }

        if (!$this->passwordHasher->verify($dto->password, $user->getPassword())) {
            throw new LoginOrPasswordIsIncorrect();
        }

        if ($clientIp) {
            $this->authRateLimiter->acceptByClientIp($clientIp);
        }

        $this->accessTokenRepository->deleteAllByUser($user);

        return $user;
    }
}
