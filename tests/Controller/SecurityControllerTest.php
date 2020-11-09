<?php


namespace App\Tests\Controller;

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

        $this->assertSame(302, $response->getStatusCode());

        $crawler = $client->followRedirect();

        $infoUser = $crawler->filter('h4')->text();

        $this->assertSame(1, $crawler->filter('a#logout')->count());
        $this->assertSame('Vous êtes connecter en tant que User1.', $infoUser);
    }

    public function testLoginWithWrongInfo()
    {
        $client = $this->CreateClient();

        $crawler = $client->request('GET', '/login');

        $this->assertSame(1, $crawler->filter('h1.connexion')->count());

        $form = $crawler->selectButton('Connexion')->form();
        $form['email'] = 'user1@gmail.com';
        $form['password'] = 'nimportequoi';
//        $form['_crsf_token'] = 'token';
        $client->submit($form);

        $response = $client->getResponse();

        $this->assertSame(302, $response->getStatusCode());

        $crawler = $client->followRedirect();

        $infoUser = $crawler->filter('div.alert-danger')->text();

        $this->assertSame('Il y a une erreur sur la saisie des informations.', $infoUser);
    }
}