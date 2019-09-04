<?php

namespace App\Repository;

use App\Entity\Periodicity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Periodicity|null find($id, $lockMode = null, $lockVersion = null)
 * @method Periodicity|null findOneBy(array $criteria, array $orderBy = null)
 * @method Periodicity[]    findAll()
 * @method Periodicity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PeriodicityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Periodicity::class);
    }

    // /**
    //  * @return Periodicity[] Returns an array of Periodicity objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Periodicity
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
