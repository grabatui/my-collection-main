<?php

namespace App\Domain\Repository;

use App\Domain\Entity\AccessToken;
use App\Domain\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AccessToken>
 *
 * @method AccessToken|null find($id, $lockMode = null, $lockVersion = null)
 * @method AccessToken|null findOneBy(array $criteria, array $orderBy = null)
 * @method AccessToken[]    findAll()
 * @method AccessToken[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccessTokenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AccessToken::class);
    }

    public function findOneByToken(string $token): ?AccessToken
    {
        return $this->findOneBy(['token' => $token]);
    }

    public function save(AccessToken $accessToken): void
    {
        $this->getEntityManager()->persist($accessToken);
        $this->getEntityManager()->flush();
    }

    public function deleteAllByUser(User $user): void
    {
        $this->createQueryBuilder('at')
            ->update()
            ->set('at.deletedAt', ':deletedAt')
            ->where('at.user = :user')
            ->andWhere('at.deletedAt IS NULL')
            ->setParameter('deletedAt', new DateTimeImmutable(), Types::DATETIME_IMMUTABLE)
            ->setParameter('user', (string)$user->getId())
            ->getQuery()
            ->execute();
    }
}
