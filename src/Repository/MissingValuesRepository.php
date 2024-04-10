<?php

namespace App\Repository;

use App\Entity\MissingValues;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MissingValues>
 *
 * @method MissingValues|null find($id, $lockMode = null, $lockVersion = null)
 * @method MissingValues|null findOneBy(array $criteria, array $orderBy = null)
 * @method MissingValues[]    findAll()
 * @method MissingValues[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MissingValuesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MissingValues::class);
    }

    //    /**
    //     * @return MissingValues[] Returns an array of MissingValues objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?MissingValues
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
