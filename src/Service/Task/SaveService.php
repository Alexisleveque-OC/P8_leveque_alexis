<?php


namespace App\Service\Task;


use App\Entity\Task;
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

    public function saveTask(Task $task)
    {

    }
}