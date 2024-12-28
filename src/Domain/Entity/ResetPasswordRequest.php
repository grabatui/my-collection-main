<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Repository\ResetPasswordRequestRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordRequestInterface;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordRequestTrait;

#[ORM\Entity(repositoryClass: ResetPasswordRequestRepository::class)]
#[ORM\Table(name: 'reset_password_request')]
#[ORM\Index(name: 'index__reset_password_request__user_id', fields: ['user'])]
class ResetPasswordRequest implements ResetPasswordRequestInterface
{
    use ResetPasswordRequestTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    #[ORM\Column(type: Types::INTEGER)]
    private int $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'resetPasswordRequests')]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;

    public static function create(
        User $user,
        \DateTimeInterface $expiresAt,
        string $selector,
        string $hashedToken,
    ): self {
        $request = new self();
        $request->initialize(
            expiresAt: $expiresAt,
            selector: $selector,
            hashedToken: $hashedToken,
        );
        $request->setUser($user);

        return $request;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUser(): object
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function setExpiresAt(\DateTimeInterface $expiresAt): void
    {
        $this->expiresAt = $expiresAt;
    }

    public function setSelector(string $selector): void
    {
        $this->selector = $selector;
    }

    public function setHashedToken(string $hashedToken): void
    {
        $this->hashedToken = $hashedToken;
    }
}
