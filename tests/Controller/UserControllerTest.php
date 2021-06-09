<?php
/**
 * Created by PhpStorm.
 * User: msi-n
 * Date: 08/06/2021
 * Time: 19:20
 */

namespace App\Tests\Controller;


use App\Repository\UsertdRepository;

class UserControllerTest extends AbstractControllerTest
{

    /** @var UserRepository */
    protected $userRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userRepository = self::$container->get(UsertdRepository::class);
    }

    public function testListActionWithoutLogin()
    {
        // If the user isn't logged, should redirect to the login page
        $this->client->request('GET', '/admin/users');
        static::assertSame(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->followRedirect();
        // Test if login field exists
        static::assertSame(1, $crawler->filter('input[name="login[email]"]')->count());
        static::assertSame(1, $crawler->filter('input[name="login[password]"]')->count());
    }

    public function testListAction()
    {
        $securityControllerTest = new SecurityControllerTest();
        $client = $securityControllerTest->testLoginAsAdmin();

        $crawler = $client->request('GET', '/admin/users');
        static::assertSame(200, $client->getResponse()->getStatusCode());
        static::assertSame("Liste des utilisateurs", $crawler->filter('h1')->text());
    }

}