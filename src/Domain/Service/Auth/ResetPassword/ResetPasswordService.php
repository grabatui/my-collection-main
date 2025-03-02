<?php

declare(strict_types=1);

namespace App\Domain\Service\Auth\ResetPassword;

use App\Domain\Entity\User;
use App\Domain\Exception\Auth\ResetPasswordException;
use App\Domain\Repository\UserRepository;
use App\Domain\Service\Password\PasswordHasher;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

readonly class ResetPasswordService
{
    public function __construct(
        private ResetPasswordHelperInterface $resetPasswordHelper,
        private PasswordHasher $passwordHasher,
        private UserRepository $userRepository,
    ) {
    }

    public function execute(
        string $token,
        string $password,
    ): void {
        try {
            /** @var User $user */
            $user = $this->resetPasswordHelper->validateTokenAndFetchUser($token);
        } catch (ResetPasswordExceptionInterface $exception) {
            throw new ResetPasswordException(
                message: $exception->getMessage(),
                previous: $exception,
            );
        }

        $user->setPassword(
            $this->passwordHasher->execute($password),
        );

        $this->userRepository->save($user);
    }
}
