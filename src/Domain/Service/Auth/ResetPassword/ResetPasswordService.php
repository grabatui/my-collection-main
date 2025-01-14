<?php

declare(strict_types=1);

namespace App\Domain\Service\Auth\ResetPassword;

use App\Domain\Entity\User;
use App\Domain\Repository\UserRepository;
use App\Domain\Service\Password\PasswordHasher;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

readonly class ResetPasswordService
{
    public function __construct(
        private ResetPasswordHelperInterface $resetPasswordHelper,
        private PasswordHasher $passwordHasher,
        private UserRepository $userRepository,
    ) {}

    public function execute(
        string $token,
        string $password,
    ): void {
        /** @var User $user */
        $user = $this->resetPasswordHelper->validateTokenAndFetchUser($token);

        $user->setPassword(
            $this->passwordHasher->execute($password),
        );

        $this->userRepository->save($user);
    }
}
