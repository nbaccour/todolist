<?php
/**
 * Created by PhpStorm.
 * User: msi-n
 * Date: 17/06/2021
 * Time: 00:42
 */

namespace App\Tests\Controller;


class HomeControllerTest extends AbstractControllerTest
{

    public function testIndex()
    {

        $crawler = $this->client->request('GET', '/');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        static::assertSame("Bienvenue sur Todo List, l'application vous permettant de gérer l'ensemble de vos tâches sans effort !",
            $crawler->filter('h1')->text());
    }

}