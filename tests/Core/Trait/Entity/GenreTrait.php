<?php

declare(strict_types=1);

namespace App\Tests\Core\Trait\Entity;

use App\Domain\Entity\Genre;
use App\Domain\Repository\GenreRepository;

trait GenreTrait
{
    public function makeGenreEntity(
        ?int $externalId = null,
    ): Genre {
        return Genre::create(
            externalId: $externalId ?? $this->getFakeNumber(),
            name: $this->getFakeWord(),
        );
    }

    public function findOrCreateGenreToDatabase(
        ?int $externalId = null,
    ): Genre {
        $externalId = $externalId ?? $this->getFakeNumber();

        $genre = $this->getService(GenreRepository::class)->findOneBy(['externalId' => $externalId]);

        if ($genre) {
            return $genre;
        }

        $genre = $this->makeGenreEntity($externalId);

        $this->getEntityManager()->persist($genre);
        $this->getEntityManager()->flush();

        return $genre;
    }
}
