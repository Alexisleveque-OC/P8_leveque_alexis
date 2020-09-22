<?php


namespace App\Service;


use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;

class TaskSaveService
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function saveTask(Task $task)
    {

    }
}