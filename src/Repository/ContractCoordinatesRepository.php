<?php

namespace App\Repository;

use App\Entity\ContractCoordinates;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ContractCoordinates>
 *
 * @method ContractCoordinates|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContractCoordinates|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContractCoordinates[]    findAll()
 * @method ContractCoordinates[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContractCoordinatesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContractCoordinates::class);
    }

    //    /**
    //     * @return ContractCoordinates[] Returns an array of ContractCoordinates objects
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

    //    public function findOneBySomeField($value): ?ContractCoordinates
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
