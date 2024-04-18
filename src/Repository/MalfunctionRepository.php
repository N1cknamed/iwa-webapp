<?php

namespace App\Repository;

use App\Entity\Malfunction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Malfunction>
 *
 * @method Malfunction|null find($id, $lockMode = null, $lockVersion = null)
 * @method Malfunction|null findOneBy(array $criteria, array $orderBy = null)
 * @method Malfunction[]    findAll()
 * @method Malfunction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MalfunctionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Malfunction::class);
    }

    //    /**
    //     * @return Malfunction[] Returns an array of Malfunction objects
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

    //    public function findOneBySomeField($value): ?Malfunction
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
