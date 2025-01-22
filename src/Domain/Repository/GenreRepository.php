<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Genre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Genre>
 *
 * @method Genre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Genre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Genre[]    findAll()
 * @method Genre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GenreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Genre::class);
    }

    /**
     * @param int[] $externalIds
     * @return array<int, Genre>
     */
    public function findByExternalIds(array $externalIds): array
    {
        $items = $this->findBy(['externalId' => $externalIds]);

        $result = [];
        foreach ($items as $item) {
            $result[$item->getExternalId()] = $item;
        }

        return $result;
    }

    /**
     * @return array<int, Genre>
     */
    public function getAllByExternalIds(): array
    {
        $items = $this->findAll();

        $result = [];
        foreach ($items as $item) {
            $result[$item->getExternalId()] = $item;
        }

        return $result;
    }

    public function save(Genre $Genre): void
    {
        $this->getEntityManager()->persist($Genre);
        $this->getEntityManager()->flush();
    }
}
