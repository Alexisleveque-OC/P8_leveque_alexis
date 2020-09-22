<?php


namespace App\Service;


use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;

class TaskRemoveService
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function removeTask(Task $task)
    {
        $this->manager->remove($task);
        $this->manager->flush();;
    }
}