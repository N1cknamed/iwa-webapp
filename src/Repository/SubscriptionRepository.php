<?php

namespace App\Repository;

use App\Entity\Subscription;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Subscription>
 *
 * @method Subscription|null find($id, $lockMode = null, $lockVersion = null)
 * @method Subscription|null findOneBy(array $criteria, array $orderBy = null)
 * @method Subscription[]    findAll()
 * @method Subscription[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubscriptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Subscription::class);
    }

    public function getActiveSubscriptions():array
    {
        $qb = $this->createQueryBuilder('s')
            ->where('s.date_end >= CURRENT_DATE()')
            ->orderBy('s.name_holder', 'ASC')
            ->groupBy('s.name_holder');

        $query = $qb->getQuery();

        return $query->execute();
    }

    public function getSubscriptions(string $name): array
    {
        $qb = $this->createQueryBuilder('s')
            ->where('s.name_holder = :name')
            ->orderBy('s.date_end', 'ASC')
            ->setParameter('name', $name);

        $query = $qb->getQuery();
        
        return $query->execute();
    }

    public function getActiveStations(string $name): array
    {
        $qb = $this->createQueryBuilder('s')
            ->where('s.name_holder = :name')
            ->andWhere('s.date_end >= CURRENT_DATE()')
            ->setParameter('name', $name);

        $query = $qb->getQuery();

        return $query->execute();
    }

    //    /**
    //     * @return Subscription[] Returns an array of Subscription objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Subscription
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
