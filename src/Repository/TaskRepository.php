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

    public function findAllTasksToDoByUser(User $user)
    {
        $qb = $this->getBaseQueryBuilder();
        self::addDoneClause($qb, false);
        self::addUserClause($qb, $user);
//        if ($user->getRoles()[0] === 'ROLE_ADMIN') {
//            self::addDoneClause($qb, false);
//            self::addUserAdminClause($qb);
//        }
//        dd($qb->getQuery()->getResult());
        return $qb->getQuery()
            ->getResult();

    }

    public function findAllTasksDoneByUser(User $user)
    {
        $qb = $this->getBaseQueryBuilder();
        self::addDoneClause($qb, true);
//        self::addUserClause($qb, $user);

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

    static function addUserAdminClause(QueryBuilder $qb)
    {
        return $qb->orWhere('t.user = :user')
            ->setParameter('user', null);
    }

}