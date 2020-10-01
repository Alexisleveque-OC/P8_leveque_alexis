<?php


namespace App\Repository;


use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use App\Entity\Task;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    public function findAllTasksToDo()
    {
        $qb = $this->getBaseQueryBuider();
        self::addDoneClause($qb, false);

        return $qb->getQuery()
            ->getResult();

//        return $this->getBaseQueryBuider()
//            ->where("t.isDone = 0")
//            ->getQuery()
//            ->getResult();
    }
    public function findAllTasksDone()
    {
        return $this->getBaseQueryBuider()
            ->where("t.isDone = 1")
            ->getQuery()
            ->getResult();
    }

    protected  function getBaseQueryBuider(){
        return $this->createQueryBuilder("t")
            ->select('t, u')
            ->leftJoin('t.user', 'u');
    }

    static function addDoneClause(QueryBuilder $qb, bool $isDone){
        return $qb->andWhere('t.isDone = :isDone')
            ->setParameter('isDone', $isDone);
    }
}