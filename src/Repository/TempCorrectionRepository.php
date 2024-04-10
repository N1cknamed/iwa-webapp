<?php

namespace App\Repository;

use App\Entity\TempCorrection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TempCorrection>
 *
 * @method TempCorrection|null find($id, $lockMode = null, $lockVersion = null)
 * @method TempCorrection|null findOneBy(array $criteria, array $orderBy = null)
 * @method TempCorrection[]    findAll()
 * @method TempCorrection[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TempCorrectionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TempCorrection::class);
    }

    //    /**
    //     * @return TempCorrection[] Returns an array of TempCorrection objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?TempCorrection
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
