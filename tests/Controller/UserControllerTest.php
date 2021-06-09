<?php
/**
 * Created by PhpStorm.
 * User: msi-n
 * Date: 08/06/2021
 * Time: 19:20
 */

namespace App\Tests\Controller;

use App\Repository\UsertdRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserControllerTest extends KernelTestCase
{

    public function testShow()
    {
//        $kernel = self::bootkernel();
//        $kernel->getContainer();

        self::bootkernel();
        $users = self::$container->get(UsertdRepository::class)->count([]);
        $this->assertEquals(13, $users);


    }

//    public function testCreate()
//    {
//
//    }
}