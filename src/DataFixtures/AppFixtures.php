<?php

namespace App\DataFixtures;

use App\Entity\Usertd;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{

    protected $slugger;
    protected $encoder;

    public function __construct(SluggerInterface $slugger, UserPasswordEncoderInterface $encoder)
    {
        $this->slugger = $slugger;
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('FR-fr');

        $admin = new Usertd();
        $hash = $this->encoder->encodePassword($admin, "password");
        $admin->setEmail('admin@gmail.com')
            ->setPassword($hash)
            ->setUsername($faker->firstName())
            ->setRoles(['ROLE_ADMIN']);

        $manager->persist($admin);

        $user = new Usertd();
        $hash = $this->encoder->encodePassword($user, "demo");
        $user->setEmail('demo@gmail.com')
            ->setUsername($faker->firstName())
            ->setPassword($hash);
        $manager->persist($user);

        $user = new Usertd();
        $hash = $this->encoder->encodePassword($user, "demoanonyme");
        $user->setEmail('anonyme@gmail.com')
            ->setUsername('Anonyme')
            ->setPassword($hash);

        $manager->persist($user);

        $aUser[] = $user;
        for ($u = 0; $u < 10; $u++) {
            $user = new Usertd();
            $hash = $this->encoder->encodePassword($user, "password");
            $user->setEmail('user' . $u . '@gmail.com')
                ->setUsername($faker->firstName())
                ->setPassword($hash);

            $manager->persist($user);
            $aUser[] = $user;
        }

        $manager->flush();
    }
}
