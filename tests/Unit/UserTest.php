<?php
/**
 * Created by PhpStorm.
 * User: msi-n
 * Date: 05/06/2021
 * Time: 23:58
 */

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Entity\Usertd;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{



    protected function setUp(): void
    {
        parent::setUp();
        $this->user = new Usertd();
    }

    public function testGetEmail(): void
    {
        $value = "test@test.fr";

        $response = $this->user->setEmail($value);

        self::assertinstanceOf(Usertd::class, $response);
        self::assertEquals($value, $this->user->getEmail());
//        self::assertEquals($value, $this->user->getUsername());
    }

}