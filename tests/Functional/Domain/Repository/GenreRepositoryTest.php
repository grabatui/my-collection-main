<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\Repository;

use App\Domain\Entity\Genre;
use App\Domain\Repository\GenreRepository;
use App\Tests\Core\KernelTestSuit;
use App\Tests\Core\Trait\Entity\GenreTrait;

/**
 * @covers \App\Domain\Repository\GenreRepository
 */
class GenreRepositoryTest extends KernelTestSuit
{
    use GenreTrait;

    private const int CUSTOM_EXTERNAL_ID = 1111111;

    protected function tearDown(): void
    {
        parent::tearDown();

        $genre = $this->getGenreRepository()->findOneBy(['externalId' => self::CUSTOM_EXTERNAL_ID]);

        if ($genre) {
            $this->getEntityManager()->remove($genre);
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @covers \App\Domain\Repository\GenreRepository::getByExternalIds
     */
    public function testHappyPathGetByExternalIds(): void
    {
        $genre1 = $this->findOrCreateGenreToDatabase();
        $genre2 = $this->findOrCreateGenreToDatabase();

        $repository = $this->getGenreRepository();

        $actualGenres = $repository->getByExternalIds([
            $genre1->getExternalId(),
            $genre2->getExternalId(),
            1111112,
        ]);

        $this->assertArrayOfEntitiesEqual(
            expectedEntities: [$genre1, $genre2],
            actualEntities: $actualGenres,
            sortFunction: static fn (Genre $aGenre, Genre $bGenre): int => $aGenre->getExternalId() <=> $bGenre->getExternalId(),
        );
    }

    /**
     * @covers \App\Domain\Repository\GenreRepository::getAllByExternalIds
     */
    public function testHappyPathGetAllByExternalIds(): void
    {
        $genre1 = $this->findOrCreateGenreToDatabase();
        $genre2 = $this->findOrCreateGenreToDatabase();

        $repository = $this->getGenreRepository();

        $actualGenres = $repository->getAllByExternalIds();

        $this->assertArrayHasKey($genre1->getExternalId(), $actualGenres);
        $this->assertArrayHasKey($genre2->getExternalId(), $actualGenres);
    }

    /**
     * @covers \App\Domain\Repository\GenreRepository::save
     */
    public function testHappyPathSave(): void
    {
        $genre = $this->makeGenreEntity(self::CUSTOM_EXTERNAL_ID);

        $repository = $this->getGenreRepository();

        $this->assertNull(
            $repository->findOneBy(['externalId' => $genre->getExternalId()])
        );

        $repository->save($genre);

        $this->assertNotNull(
            $repository->findOneBy(['externalId' => $genre->getExternalId()])
        );
    }

    private function getGenreRepository(): GenreRepository
    {
        /** @var GenreRepository $repository */
        $repository = $this->getService(GenreRepository::class);

        return $repository;
    }
}
