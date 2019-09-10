<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Task|null find($id, $lockMode = null, $lockVersion = null)
 * @method Task|null findOneBy(array $criteria, array $orderBy = null)
 * @method Task[]    findAll()
 * @method Task[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    public function findAllByPriorityAndOwner($priority_id, $user_id)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.priority = :priority_id')
            ->setParameter('priority_id', $priority_id)
            ->andWhere('t.owner = :user_id')
            ->setParameter('user_id', $user_id)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findAllByPeriodicityAndOwner($periodicity_code, $user_id)
    {
        return $this->createQueryBuilder('t')
            ->innerJoin('t.periodicity', 'p')
            ->andWhere('p.code like :periodicity_code')
            ->setParameter('periodicity_code', $periodicity_code)
            ->andWhere('t.owner = :user_id')
            ->setParameter('user_id', $user_id)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findWithUserVerification($task_id, $user_id)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.id = :task_id')
            ->setParameter('task_id', $task_id)
            ->andWhere('t.owner = :user_id')
            ->setParameter('user_id', $user_id)
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return Task[] Returns an array of Task objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Task
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
