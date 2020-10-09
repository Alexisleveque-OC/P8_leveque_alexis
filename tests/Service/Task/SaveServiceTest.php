<?php


namespace App\Tests\Service\Task;


use App\Entity\Task;
use App\Entity\User;
use App\Service\Task\SaveService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class SaveServiceTest extends TestCase
{
    /**
     * @var SaveService
     */
    private SaveService $SaveService;

    public function setUp()
    {
        $manager = $this->createMock(EntityManagerInterface::class);
        $this->SaveService = new SaveService($manager);

    }

    public function taskProvider()
    {
        $task = new Task();
        $task->setTitle('toto');
        $task->setContent('coucou');

        return [[$task]];
    }

    /**
     * @dataProvider taskProvider
     * @param Task $task
     */
    public function testSaveTask(Task $task)
    {
        $user = new User();

        $test = $this->SaveService->saveTask($task, $user);

        $this->assertEquals($test->getTitle(), 'toto');
        $this->assertInstanceOf(DateTime::class, $test->getCreatedAt());
        $this->assertInstanceOf(Task::class, $test);
        $this->assertInstanceOf(User::class, $test->getUser());

    }

}