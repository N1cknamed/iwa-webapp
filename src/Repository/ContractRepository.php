<?php

namespace App\Repository;

use App\Entity\Contract;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Contract>
 *
 * @method Contract|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contract|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contract[]    findAll()
 * @method Contract[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContractRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contract::class);
    }

    public function getActiveContracts():array
    {
        $qb = $this->createQueryBuilder('c')
            ->where('c.date_end >= CURRENT_DATE()')
            ->orderBy('c.name_holder', 'ASC')
            ->groupBy('c.name_holder');

        $query = $qb->getQuery();

        return $query->execute();
    }

    public function getContracts(string $name): array
    {
        $qb = $this->createQueryBuilder('c')
            ->where('c.name_holder = :name')
            ->orderBy('c.date_end', 'ASC')
            ->setParameter('name', $name);

        $query = $qb->getQuery();
        
        return $query->execute();
    }

    public function getActiveData(string $name): array
    {
        $qb = $this->createQueryBuilder('c')
            ->where('c.name_holder = :name')
            ->andWhere('c.date_end >= CURRENT_DATE()')
            ->setParameter('name', $name);

        $query = $qb->getQuery();

        return $query->execute();
    }

    //    /**
    //     * @return Contract[] Returns an array of Contract objects
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

    //    public function findOneBySomeField($value): ?Contract
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
