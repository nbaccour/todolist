<?php
/**
 * Created by PhpStorm.
 * User: msi-n
 * Date: 11/06/2021
 * Time: 14:30
 */

namespace App\Tests\Controller;


use App\Entity\Task;
use App\Repository\TaskRepository;

class TaskControllerTest extends AbstractControllerTest
{

    /** @var TaskRepository */
    protected $taskRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->taskRepository = self::$container->get(TaskRepository::class);
    }


    public function testListTask(): void
    {
        $this->client->request('GET', '/tasks');
        self::assertEquals(302, $this->client->getResponse()->getStatusCode());

        $this->loginWithAdmin();

        $crawler = $this->client->request('GET', '/tasks');
        self::assertEquals(200, $this->client->getResponse()->getStatusCode());
        static::assertSame("Liste des taches", $crawler->filter('h1')->text());
    }

    public function testMyTask(): void
    {
        $this->client->request('GET', '/mytask');
        self::assertEquals(302, $this->client->getResponse()->getStatusCode());

        $this->loginWithAdmin();

        $crawler = $this->client->request('GET', '/mytask');
        self::assertEquals(200, $this->client->getResponse()->getStatusCode());
        static::assertSame("Mes Taches :", $crawler->filter('h1')->text());
    }

    public function testCreate(): void
    {
        $this->client->request('GET', '/tasks/create');
        self::assertEquals(302, $this->client->getResponse()->getStatusCode());

        $this->loginWithAdmin();

        $crawler = $this->client->request('GET', '/tasks/create');
        self::assertEquals(200, $this->client->getResponse()->getStatusCode());
        self::assertCount(3, $crawler->filter('input'));
        self::assertEquals('Ajouter', $crawler->filter('button.btn.btn-success')->text());

        $buttonCrawlerMode = $crawler->filter('form');
        $form = $buttonCrawlerMode->form([
            'task[title]'   => 'Titre de la tâche test',
            'task[content]' => 'Description de la tâche test',
        ]);

        $this->client->submit($form);
        self::assertEquals(302, $this->client->getResponse()->getStatusCode());
        $crawler = $this->client->followRedirect();
        self::assertEquals('task_mytask', $this->client->getRequest()->get('_route'));
        self::assertEquals(
            'La tâche a été bien été ajoutée.',
            $crawler->filter('div.alert.alert-success')->text(null, true)
        );

        $task = $this->taskRepository->findOneBy(['title' => 'Titre de la tâche test']);
        self::assertInstanceOf(Task::class, $task);
        self::assertEquals('Titre de la tâche test', $task->getTitle());
        self::assertEquals('Description de la tâche test', $task->getContent());
    }

    public function testEdit(): void
    {
        $this->client->request('GET', '/tasks/modify/61');
        self::assertEquals(302, $this->client->getResponse()->getStatusCode());

        $this->loginWithAdmin();

        $crawler = $this->client->request('GET', '/tasks/modify/61');
        self::assertEquals(200, $this->client->getResponse()->getStatusCode());
        self::assertCount(3, $crawler->filter('input'));
        self::assertEquals('Modifier les données de la tache', $crawler->filter('button.btn.btn-success')->text());

        $buttonCrawlerMode = $crawler->filter('form');
        $form = $buttonCrawlerMode->form([
            'taskmodify[title]'   => 'Titre de la tâche modif',
            'taskmodify[content]' => 'Description de la tâche modif',
            'taskmodify[isDone]'  => 1,
        ]);

        $this->client->submit($form);
        self::assertEquals(302, $this->client->getResponse()->getStatusCode());
        $crawler = $this->client->followRedirect();
        self::assertEquals('task_mytask', $this->client->getRequest()->get('_route'));
        self::assertEquals(
            'La tâche a bien été modifiée.',
            $crawler->filter('div.alert.alert-success')->text(null, true)
        );

        $task = $this->taskRepository->findOneBy(['title' => 'Titre de la tâche modif']);
        self::assertInstanceOf(Task::class, $task);
        self::assertEquals('Titre de la tâche modif', $task->getTitle());
        self::assertEquals('Description de la tâche modif', $task->getContent());
    }

    public function testDelete(): void
    {
        $this->client->request('DELETE', '/tasks/delete/61');
        self::assertEquals(302, $this->client->getResponse()->getStatusCode());

        $this->loginWithAdmin();

        $this->client->request('DELETE', '/tasks/delete/61');
        self::assertEquals(302, $this->client->getResponse()->getStatusCode());
        $crawler = $this->client->followRedirect();
        self::assertEquals('task_mytask', $this->client->getRequest()->get('_route'));
        self::assertEquals(
            'La tâche a bien été supprimée.',
            $crawler->filter('div.alert.alert-warning')->text(null, true)
        );

        $task = $this->taskRepository->findOneBy(['title' => 'tache de test']);
        self::assertEmpty($task);
    }
}