<?php

declare(strict_types=1);

namespace App\Domain\Service\Auth\Logout;

use App\Domain\Entity\User;
use App\Domain\Repository\AccessTokenRepository;

readonly class LogoutService
{
    public function __construct(
        private AccessTokenRepository $accessTokenRepository,
    ) {
    }

    public function execute(User $user): void
    {
        $this->accessTokenRepository->deleteAllByUser($user);
    }
}
