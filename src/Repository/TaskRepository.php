<?php


namespace App\Repository;


use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    public function findAllTasksToDoByUser(User $user, $withAnonymous = false)
    {
        $qb = $this->getBaseQueryBuilder();
        self::addDoneClause($qb, false);

        if ($withAnonymous) {
            self::addUserAndAnonymousClause($qb, $user);

        } else {
            self::addUserClause($qb, $user);

        }
        return $qb->getQuery()
            ->getResult();

    }

    public function findAllTasksDoneByUser(User $user, $withAnonymous = false)
    {
        $qb = $this->getBaseQueryBuilder();
        self::addDoneClause($qb, true);

        if ($withAnonymous) {
            self::addUserAndAnonymousClause($qb, $user);

        } else {
            self::addUserClause($qb, $user);

        }

        return $qb->getQuery()
            ->getResult();
    }

    protected function getBaseQueryBuilder()
    {
        return $this->createQueryBuilder("t")
            ->select('t, u')
            ->leftJoin('t.user', 'u');
    }

    static function addDoneClause(QueryBuilder $qb, bool $isDone)
    {
        return $qb->andWhere('t.isDone = :isDone')
            ->setParameter('isDone', $isDone);
    }

    static function addUserClause(QueryBuilder $qb, User $user)
    {
        return $qb->andWhere('t.user = :user ')
            ->setParameter('user', $user);
    }

    static function addUserAndAnonymousClause(QueryBuilder $qb, User $user)
    {
        return $qb->andWhere('t.user = :user OR t.user IS NULL')
            ->setParameter('user', $user);
    }

}