<?php

namespace App\Repository;

use App\Entity\NearestLocation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<NearestLocation>
 *
 * @method NearestLocation|null find($id, $lockMode = null, $lockVersion = null)
 * @method NearestLocation|null findOneBy(array $criteria, array $orderBy = null)
 * @method NearestLocation[]    findAll()
 * @method NearestLocation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NearestLocationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NearestLocation::class);
    }

//    /**
//     * @return NearestLocation[] Returns an array of NearestLocation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('n.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?NearestLocation
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
