<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Country;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Country>
 *
 * @method Country|null find($id, $lockMode = null, $lockVersion = null)
 * @method Country|null findOneBy(array $criteria, array $orderBy = null)
 * @method Country[]    findAll()
 * @method Country[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CountryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Country::class);
    }

    /**
     * @param string[] $codes
     * @return array<string, Country>
     */
    public function getByCodes(array $codes): array
    {
        $items = $this->findBy(['code' => $codes]);

        $result = [];
        foreach ($items as $item) {
            $result[$item->getCode()] = $item;
        }

        return $result;
    }

    /**
     * @return array<string, Country>
     */
    public function getAllByCodes(): array
    {
        $items = $this->findAll();

        $result = [];
        foreach ($items as $item) {
            $result[$item->getCode()] = $item;
        }

        return $result;
    }

    public function save(Country $country): void
    {
        $this->getEntityManager()->persist($country);
        $this->getEntityManager()->flush();
    }
}
