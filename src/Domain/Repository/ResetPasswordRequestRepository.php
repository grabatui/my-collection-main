<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\ResetPasswordRequest;
use App\Domain\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use RuntimeException;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordRequestInterface;
use SymfonyCasts\Bundle\ResetPassword\Persistence\Repository\ResetPasswordRequestRepositoryTrait;
use SymfonyCasts\Bundle\ResetPassword\Persistence\ResetPasswordRequestRepositoryInterface;

class ResetPasswordRequestRepository extends ServiceEntityRepository implements ResetPasswordRequestRepositoryInterface
{
    use ResetPasswordRequestRepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ResetPasswordRequest::class);
    }

    public function createResetPasswordRequest(
        object $user,
        \DateTimeInterface $expiresAt,
        string $selector,
        string $hashedToken,
    ): ResetPasswordRequestInterface {
        if (!($user instanceof User)) {
            throw new RuntimeException('Wrong user class: ' . get_class($user));
        }

        return ResetPasswordRequest::create(
            user: $user,
            expiresAt: $expiresAt,
            selector: $selector,
            hashedToken: $hashedToken,
        );
    }
}
