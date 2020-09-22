<?php


namespace App\Service\Task\Task;


use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;

class RemoveService
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