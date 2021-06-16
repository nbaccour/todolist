<?php
/**
 * Created by PhpStorm.
 * User: msi-n
 * Date: 08/06/2021
 * Time: 23:02
 */

namespace App\Tests\Controller;


class SecurityControllerTest extends AbstractControllerTest
{


    public function testLoginAsAdmin()
    {

        $crawler = $this->client->request('GET', '/login');
        static::assertSame(200, $this->client->getResponse()->getStatusCode());

        // Test if login field exists
        static::assertSame(1, $crawler->filter('input[name="login[email]"]')->count());
        static::assertSame(1, $crawler->filter('input[name="login[password]"]')->count());

        $form = $crawler->selectButton('Connexion')->form();
        $form['login[email]'] = 'admin@gmail.com';
        $form['login[password]'] = 'password';
        $this->client->submit($form);

        $crawler = $this->client->followRedirect();
        static::assertSame(200, $this->client->getResponse()->getStatusCode());

        // Test if home page text when authenticated exists
        static::assertSame("Liste des tâches", $crawler->filter('h1')->text());
        // Return the client to reuse the authenticated user admin it in others functionnal tests
        return $this->client;

    }

    public function testLoginWithValidData(): void
    {

        $crawler = $this->client->request('GET', '/login');
        static::assertSame(200, $this->client->getResponse()->getStatusCode());

        // Test if login field exists
        static::assertSame(1, $crawler->filter('input[name="login[email]"]')->count());
        static::assertSame(1, $crawler->filter('input[name="login[password]"]')->count());

        $form = $crawler->selectButton('Connexion')->form();
        $form['login[email]'] = 'user4@gmail.com';
        $form['login[password]'] = 'password';
        $this->client->submit($form);

        $crawler = $this->client->followRedirect();
        static::assertSame(200, $this->client->getResponse()->getStatusCode());

        // Test if home page text when authenticated exists
        static::assertSame("Liste des tâches", $crawler->filter('h1')->text());

    }




}