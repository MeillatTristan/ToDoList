<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
            $user = (new User())
                ->setEmail("admin@test.fr")
                ->setPassword("Admin123")
                ->setPseudo('admin')
                ->setRoles(['ROLE_ADMIN']);
            $manager->persist($user);

        $manager->flush();
    }


}
