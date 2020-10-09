<?php


namespace App\Tests\Controller;


use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{

    public function testUserCreation()
    {
        $client = $this->CreateClient();

        $userRepository = static::$container->get(UserRepository::class);
        $adminTest = $userRepository->findOneBy(['username' => 'Admin']);
        $client->loginUser($adminTest);

        $crawler = $client->request('GET', '/users/create');

        $this->assertSame(1, $crawler->filter('h1.registration')->count());

        $form = $crawler->selectButton('Ajouter')->form();
        $form['user[username]'] = 'UserTest';
        $form['user[password][first]'] = 'test';
        $form['user[password][second]'] = 'test';
        $form['user[email]'] = 'test@test.com';
        $client->submit($form);

        $userTest = $userRepository->findOneBy(['username' => 'UserTest']);

        $this->assertInstanceOf(User::class,$userTest);
        $this->assertSame('UserTest',$userTest->getUsername());
        $this->assertSame('ROLE_USER',$userTest->getRoles()[0]);

    }

    public function testEditUser()
    {
        $client = $this->CreateClient();

        $userRepository = static::$container->get(UserRepository::class);
        $adminTest = $userRepository->findOneBy(['username' => 'Admin']);
        $client->loginUser($adminTest);

        $crawler = $client->request('GET', '/users/2/edit');

        $this->assertSame(1, $crawler->filter('h1.userEdition')->count());

        $testUserBefore = $userRepository->findOneBy(['id'=>'2']);

        $form = $crawler->selectButton('Modifier')->form();
        $form['user[username]'] = 'UserTest';
        $form['user[password][first]'] = 'test';
        $form['user[password][second]'] = 'test';
        $form['user[email]'] = 'test@test.com';
//        $form['user[roles][]'] = "ROLE_ADMIN";
        $client->submit($form);

        $testUserAfter = $userRepository->findOneBy(['id'=>'2']);

        $this->assertInstanceOf(User::class,$testUserAfter);
        $this->assertNotSame($testUserAfter->getUsername(),$testUserBefore->getUsername());
        $this->assertSame('UserTest',$testUserAfter->getUsername());
//        $this->assertSame('ROLE_ADMIN',$testUserAfter->getRoles()[0]);

    }

}