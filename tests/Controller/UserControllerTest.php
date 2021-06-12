<?php
/**
 * Created by PhpStorm.
 * User: msi-n
 * Date: 08/06/2021
 * Time: 19:20
 */

namespace App\Tests\Controller;


use App\Entity\Usertd;
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

    public function testListUsersWithoutLogin()
    {

        // If the user isn't logged, should redirect to the login page
        $this->client->request('GET', '/admin/users');
        static::assertSame(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->followRedirect();
        // Test if login field exists
        static::assertSame(1, $crawler->filter('input[name="login[email]"]')->count());
        static::assertSame(1, $crawler->filter('input[name="login[password]"]')->count());
    }

    public function testShowProfile()
    {
        $this->client->request('GET', '/account');
        self::assertEquals(302, $this->client->getResponse()->getStatusCode());

        $this->loginWithUser();

        $crawler = $this->client->request('GET', '/account');
        self::assertEquals(200, $this->client->getResponse()->getStatusCode());
        self::assertSame('Mes données', $crawler->filter('div.card-header')->text());

    }

    public function testResetPassword(): void
    {
        $this->client->request('GET', '/resetpwd');
        self::assertEquals(302, $this->client->getResponse()->getStatusCode());

        $this->loginWithUser();

        $crawler = $this->client->request('GET', '/resetpwd');
        self::assertEquals(200, $this->client->getResponse()->getStatusCode());
        self::assertCount(3, $crawler->filter('input'));
        self::assertEquals('Modifier', $crawler->filter('button.btn.btn-success')->text());

        $buttonCrawlerMode = $crawler->filter('form');
        $form = $buttonCrawlerMode->form([
            'reset_password[password]'      => 'password',
            'reset_password[verifPassword]' => 'password',
        ]);

        $this->client->submit($form);
//        self::assertEquals(200, $this->client->getResponse()->getStatusCode());
//        $crawler = $this->client->followRedirect();
//        self::assertEquals('user_resetPassword', $this->client->getRequest()->get('_route'));
//        self::assertEquals(
//            'Votre mot de passe a été modifié',
//            $crawler->filter('div.alert.alert-success')->text(null, true)
//        );

    }


    public function testListUsers(): void
    {


        $this->client->request('GET', '/admin/users');
        self::assertEquals(302, $this->client->getResponse()->getStatusCode());

        $this->loginWithAdmin();

        $crawler = $this->client->request('GET', '/admin/users');
        self::assertEquals(200, $this->client->getResponse()->getStatusCode());
        static::assertSame("Liste des utilisateurs", $crawler->filter('h1')->text());
    }

    public function testCreate(): void
    {
        $this->client->request('GET', '/admin/user/create');
        self::assertEquals(302, $this->client->getResponse()->getStatusCode());

        $this->loginWithAdmin();

        $crawler = $this->client->request('GET', '/admin/user/create');
        self::assertEquals(200, $this->client->getResponse()->getStatusCode());
        self::assertStringContainsString('Ajouter un utilisateur', $crawler->filter('h1')->text());
        self::assertContains('Ajouter', [$crawler->filter('button.btn.btn-success')->text()]);
        self::assertCount(5, $crawler->filter('input'));

        $buttonCrawlerMode = $crawler->filter('form');
        $form = $buttonCrawlerMode->form([
            'user[username]'      => 'admin55',
            'user[email]'         => 'admin55@gmail.com',
            'user[password]'      => 'password',
            'user[verifPassword]' => 'password',
            'user[roles]'         => 'ROLE_ADMIN',
        ]);

        $this->client->submit($form);

        self::assertEquals(302, $this->client->getResponse()->getStatusCode());
        $crawler = $this->client->followRedirect();
        self::assertEquals('user_show', $this->client->getRequest()->get('_route'));
        self::assertEquals(
            "L'utilisateur a bien été ajouté.",
            $crawler->filter('div.alert.alert-success')->text(null, true)
        );

        $user = $this->userRepository->findOneBy(['username' => 'admin55']);
        self::assertInstanceOf(Usertd::class, $user);
        self::assertEquals('admin55', $user->getUsername());
        self::assertEquals('admin55@gmail.com', $user->getEmail());
        self::assertEquals('ROLE_ADMIN', $user->getRoles()[0]);
    }

    public function testDelete(): void
    {
        $this->client->request('DELETE', '/admin/user/delete/118');
        self::assertEquals(302, $this->client->getResponse()->getStatusCode());

        $this->loginWithAdmin();

        $this->client->request('DELETE', '/admin/user/delete/118');
        self::assertEquals(302, $this->client->getResponse()->getStatusCode());
        $crawler = $this->client->followRedirect();
        self::assertEquals('user_show', $this->client->getRequest()->get('_route'));
        self::assertEquals(
            "L'utilisateur a bien été suprimé",
            $crawler->filter('div.alert.alert-warning')->text(null, true)
        );

        $user = $this->userRepository->findOneBy(['email' => 'user8@gmail.com']);
        self::assertEmpty($user);
    }

    public function testModify(): void
    {
        $this->client->request('GET', '/admin/user/modify/97');
        self::assertEquals(302, $this->client->getResponse()->getStatusCode());

        $this->loginWithAdmin();

        $crawler = $this->client->request('GET', '/admin/user/modify/97');
        self::assertEquals(200, $this->client->getResponse()->getStatusCode());
        self::assertContains("Modifier les données de l'utilisateur",
            [$crawler->filter('button.btn.btn-success')->text()]);
        self::assertCount(3, $crawler->filter('input'));

        $buttonCrawlerMode = $crawler->filter('form');
        $form = $buttonCrawlerMode->form([
            'usermodify[username]' => 'modifTabitha',
            'usermodify[email]'    => 'modifuser0@gmail.com',
            'usermodify[roles]'    => 'ROLE_ADMIN',
        ]);

        $this->client->submit($form);

        self::assertEquals(302, $this->client->getResponse()->getStatusCode());
        $crawler = $this->client->followRedirect();
        self::assertEquals('user_show', $this->client->getRequest()->get('_route'));
        self::assertEquals(
            "L'utilisateur a bien été modifié",
            $crawler->filter('div.alert.alert-success')->text(null, true)
        );

        $user = $this->userRepository->findOneBy(['username' => 'modifTabitha']);
        self::assertInstanceOf(Usertd::class, $user);
        self::assertEquals('modifTabitha', $user->getUsername());
        self::assertEquals('modifuser0@gmail.com', $user->getEmail());
        self::assertEquals('ROLE_ADMIN', $user->getRoles()[0]);
    }


}