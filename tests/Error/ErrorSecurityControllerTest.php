<?php
/**
 * Created by PhpStorm.
 * User: msi-n
 * Date: 09/06/2021
 * Time: 17:20
 */

namespace App\Tests\Error;

use App\Tests\Controller\AbstractControllerTest;

class ErrorSecurityControllerTest extends AbstractControllerTest
{

    public function testLoginWithInvalidData(): void
    {
        $crawler = $this->client->request('GET', '/login');
        self::assertEquals(200, $this->client->getResponse()->getStatusCode());

        $buttonCrawlerMode = $crawler->filter('form');
        $form = $buttonCrawlerMode->form([
            'login[email]'    => 'test',
            'login[password]' => 'test',
        ]);

        $this->client->submit($form);

        self::assertEquals(302, $this->client->getResponse()->getStatusCode());
        $crawler = $this->client->followRedirect();
        self::assertEquals(
            'Vos identifiants sont invalides !',
            $crawler->filter('div.alert.alert-danger')->text(null, true)
        );
    }


    public function testLoginWithInvalidPassword(): void
    {
        $crawler = $this->client->request('GET', '/login');
        self::assertEquals(200, $this->client->getResponse()->getStatusCode());

        $buttonCrawlerMode = $crawler->filter('form');
        $form = $buttonCrawlerMode->form([
            'login[email]' => 'user0@gmail.com',
            'login[password]' => 'test'
        ]);

        $this->client->submit($form);

        self::assertEquals(302, $this->client->getResponse()->getStatusCode());
        $crawler = $this->client->followRedirect();
        self::assertEquals(
            'Vos identifiants sont invalides !',
            $crawler->filter('div.alert.alert-danger')->text(null, true)
        );
    }
}