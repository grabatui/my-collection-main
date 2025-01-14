<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Repository\AccessTokenRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AccessTokenRepository::class)]
#[ORM\Table(name: 'access_token')]
#[ORM\Index(name: 'index__access_token__user_id', fields: ['user'])]
#[ORM\Index(name: 'index__access_token__access_token', fields: ['accessToken'])]
#[ORM\Index(name: 'index__access_token__refresh_token', fields: ['refreshToken'])]
class AccessToken
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    #[ORM\Column(type: Types::INTEGER)]
    private int $id;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $accessToken;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $refreshToken;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'accessTokens')]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private \DateTimeInterface $createdAt;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeInterface $deletedAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public static function create(
        User $user,
        string $accessToken,
        string $refreshToken,
    ): self {
        $entity = new self();
        $entity->setUser($user);
        $entity->setAccessToken($accessToken);
        $entity->setRefreshToken($refreshToken);

        return $entity;
    }

    public function delete(): void
    {
        $this->setDeletedAt(new \DateTimeImmutable());
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    public function setAccessToken(string $accessToken): void
    {
        $this->accessToken = $accessToken;
    }

    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    public function setRefreshToken(string $refreshToken): void
    {
        $this->refreshToken = $refreshToken;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getDeletedAt(): ?\DateTimeInterface
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?\DateTimeInterface $deletedAt): void
    {
        $this->deletedAt = $deletedAt;
    }
}
