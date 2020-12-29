<?php


namespace App\Tests\Controller;


use App\Entity\Task;
use App\Entity\User;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    public function testTaskCreation()
    {
        $client = $this->CreateClient();

        $userRepository = static::$container->get(UserRepository::class);
        $user = $userRepository->findOneBy(['username' => 'User1']);
        $client->loginUser($user);

        $taskRepository = static::$container->get(TaskRepository::class);

        $crawler = $client->request('GET', '/tasks/create');

        $this->assertSame(1, $crawler->filter('h1.taskCreation')->count());

        $form = $crawler->selectButton('Ajouter')->form();
        $form['task[title]'] = 'TitleTest';
        $form['task[content]'] = 'ContentTest';
        $client->submit($form);

        $taskTest = $taskRepository->findOneBy(['title' => 'TitleTest']);

        $this->assertInstanceOf(Task::class,$taskTest);
        $this->assertInstanceOf(User::class,$taskTest->getUser());
        $this->assertSame('TitleTest',$taskTest->getTitle());
        $this->assertEquals($user->getId(),$taskTest->getUser()->getId());

    }

    public function testEditTask()
    {
        $client = $this->CreateClient();

        $userRepository = static::$container->get(UserRepository::class);
        $user = $userRepository->findOneBy(['username' => 'User1']);
        $client->loginUser($user);

        $taskRepository = static::$container->get(TaskRepository::class);
        // task with id = 101 is created by User1
        $taskBefore = $taskRepository->findOneBy(['id'=>'101']);

        $crawler = $client->request('GET', '/tasks/101/edit');

        $this->assertSame(1, $crawler->filter('h1.taskEdit')->count());

        $form = $crawler->selectButton('Modifier')->form();
        $form['task[title]'] = 'TitleTest';
        $form['task[content]'] = 'ContentTest';
        $client->submit($form);

        $taskAfter = $taskRepository->findOneBy(['id' => '101']);


        $this->assertInstanceOf(Task::class,$taskAfter);
        $this->assertInstanceOf(User::class,$taskAfter->getUser());
        $this->assertSame('TitleTest',$taskAfter->getTitle());
        $this->assertSame('ContentTest',$taskAfter->getContent());
        $this->assertNotSame($taskBefore->getTitle(), $taskAfter->getTitle());

    }
}