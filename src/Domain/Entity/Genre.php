<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Repository\GenreRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GenreRepository::class)]
#[ORM\Cache(usage: 'NONSTRICT_READ_WRITE', region: 'dictionaries')]
class Genre
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    #[ORM\Column(type: Types::INTEGER)]
    private int $id;

    #[ORM\Column(type: Types::INTEGER)]
    private int $externalId;

    #[ORM\Column(type: Types::STRING)]
    private string $name;

    public static function create(
        int $externalId,
        string $name,
    ): self {
        $genre = new self();
        $genre->setExternalId($externalId);
        $genre->setName($name);

        return $genre;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getExternalId(): int
    {
        return $this->externalId;
    }

    public function setExternalId(int $externalId): void
    {
        $this->externalId = $externalId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
