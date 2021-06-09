<?php
/**
 * Created by PhpStorm.
 * User: msi-n
 * Date: 07/06/2021
 * Time: 18:23
 */

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\Usertd;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
        $this->task = new Task();
    }


    public function testGetId()
    {
        static::assertEquals($this->task->getId(), null);
    }

    public function testGetTitle()
    {
        $this->task->setTitle('Test title');
        static::assertEquals($this->task->getTitle(), 'Test title');
    }

    public function testGetContent()
    {
        $this->task->setContent('Test content');
        static::assertEquals($this->task->getContent(), 'Test content');
    }

    public function testGetCreatedAt()
    {
        $this->task->setCreateAt(new \DateTime);
        static::assertInstanceOf(\DateTime::class, $this->task->getCreateAt());
    }

    public function testGetUser()
    {
        $this->task->setUsertd(new Usertd());
        static::assertInstanceOf(Usertd::class, $this->task->getUsertd());
    }

}