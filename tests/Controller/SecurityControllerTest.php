<?php


namespace App\Tests\Controller;


use App\Entity\Task;
use App\Entity\User;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    public function testLogin()
    {
        $client = $this->CreateClient();

        $crawler = $client->request('GET', '/login');

        $this->assertSame(1, $crawler->filter('h1.connexion')->count());

        $form = $crawler->selectButton('Connexion')->form();
        $form['email'] = 'user1@gmail.com';
        $form['password'] = 'coucou';
//        $form['_crsf_token'] = 'token';
        $client->submit($form);

        $response = $client->getResponse();
//        dump($response);

        $this->assertSame(302, $response->getStatusCode());

//        $client->followRedirect();
        dump($client);
        $this->assertSame(1, $crawler->filter('a.logout')->count());

//        $this->assert

    }

}