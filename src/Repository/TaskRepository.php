<?php


namespace App\Repository;


use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use App\Entity\Task;
use Doctrine\Persistence\ManagerRegistry;

class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    public function findAllTasksToDo()
    {
        return $this->createQueryBuilder("t")
            ->select()
            ->where("t.isDone = 0")
            ->getQuery()
            ->getArrayResult();
    }
    public function findAllTasksDone()
    {
        return $this->createQueryBuilder("t")
            ->select()
            ->where("t.isDone = 1")
            ->getQuery()
            ->getArrayResult();
    }
}