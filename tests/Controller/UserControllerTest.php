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
        $testUser = $userRepository->findOneBy(['username' => 'Admin']);
        $client->loginUser($testUser);

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

}