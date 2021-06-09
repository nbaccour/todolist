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
    public function testLoginWithValidData(): void
    {
        $crawler = $this->client->request('GET', '/login');
        self::assertEquals(200, $this->client->getResponse()->getStatusCode());
        self::assertCount(3, $crawler->filter('input'));
        self::assertContains('CONNEXION', $crawler->filter('btn btn-success')->text());

//        $this->loginWithAdmin();
//
//        self::assertEquals(302, $this->client->getResponse()->getStatusCode());
//        $crawler = $this->client->followRedirect();
//
//        self::assertContains('Créer une nouvelle tâche', $crawler->filter('a.btn.btn-success.btn-sm.mb-2')->text());
//        self::assertContains(
//            'Consulter la liste des tâches à faire',
//            $crawler->filter('a.btn.btn-info.btn-sm.mb-2')->text()
//        );
//        self::assertContains(
//            "Bienvenue sur Todo List, l'application vous permettant de gérer l'ensemble de vos tâches sans effort !",
//            $crawler->filter('h1')->text()
//        );
    }

}