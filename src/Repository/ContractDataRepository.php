<?php

namespace App\Repository;

use App\Entity\ContractData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ContractData>
 *
 * @method ContractData|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContractData|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContractData[]    findAll()
 * @method ContractData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContractDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContractData::class);
    }

    //    /**
    //     * @return ContractData[] Returns an array of Contractdata objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?ContractData
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
