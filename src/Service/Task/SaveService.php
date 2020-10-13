<?php


namespace App\Service\Task;


use App\Entity\Task;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class SaveService
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function saveTask(Task $task, User $user = null)
    {
        if ($user !== null) {
            $task->setUser($user);
        }

        $this->manager->persist($task);
        $this->manager->flush();

        return $task;
    }
}