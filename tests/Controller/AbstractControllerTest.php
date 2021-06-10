<?php
/**
 * Created by PhpStorm.
 * User: msi-n
 * Date: 09/06/2021
 * Time: 00:08
 */

namespace App\Tests\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class AbstractControllerTest extends WebTestCase
{

    protected $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function loginWithAdmin(): void
    {
        $crawler = $this->client->request('GET', '/login');

        $buttonCrawlerMode = $crawler->filter('form');
        $form = $buttonCrawlerMode->form([
            'login[username]' => 'admin@gmail.com',
            'login[password]' => 'password',
        ]);

        $this->client->submit($form);
    }


    public function loginWithUser(): void
    {
        $crawler = $this->client->request('GET', '/login');

        $buttonCrawlerMode = $crawler->filter('form');
        $form = $buttonCrawlerMode->form([
            'login[username]' => 'demo@gmail.com',
            'login[password]' => 'demo',
        ]);

        $this->client->submit($form);
    }
}