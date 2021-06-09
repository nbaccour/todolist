<?php
/**
 * Created by PhpStorm.
 * User: msi-n
 * Date: 05/06/2021
 * Time: 23:58
 */

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\Usertd;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{


    protected function setUp(): void
    {
        parent::setUp();
        $this->user = new Usertd();
    }

    public function testGetId()
    {
        static::assertEquals($this->user->getId(), null);
    }

    public function testGetUsername()
    {
        $this->user->setUsername('Test username');
        static::assertEquals($this->user->getUsername(), 'Test username');
    }

    public function testGetPassword()
    {
        $this->user->setPassword('Test password');
        static::assertEquals($this->user->getPassword(), 'Test password');
    }

    public function testGetRoles()
    {
        $this->user->setRoles(['ROLE_ADMIN']);
        static::assertEquals($this->user->getRoles(), ['ROLE_ADMIN', 'ROLE_USER']);
    }

    public function testGetEmail(): void
    {
        $value = "test@test.fr";

        $response = $this->user->setEmail($value);

        $this->assertInstanceOf(Usertd::class, $response);
        $this->assertEquals($value, $this->user->getEmail());

    }

    public function testGetAddTask()
    {
        static::assertInstanceOf(Usertd::class, $this->user->addTask(new Task()));
//        static::assertInstanceOf(ArrayCollection::class, $this->user->getTask());
        static::assertContainsOnlyInstancesOf(Task::class, $this->user->getTask());
    }

    public function testRemoveTask()
    {
        // If there is not the Task in the ArrayCollection
        static::assertInstanceOf(Usertd::class, $this->user->removeTask(new Task()));
        static::assertEmpty($this->user->getTask());

        // If there is the Task in the ArrayCollection
        $task = new Task();
        $this->user->addTask($task);
        $this->user->removeTask($task);
        static::assertEmpty($this->user->getTask());
        static::assertInstanceOf(Usertd::class, $this->user->removeTask(new Task()));
    }

}