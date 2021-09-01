<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TaskFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i=0; $i < 10; $i++) { 
            $task = new Task();
            $task->setTitle("title$i");
            $task->setContent("content$i");
            $user = new User();
            $user->setEmail("user$i@todo.com");
            $user->setPassword("0000");
            $user->setPseudo("user$i");
            $task->setUser($user);
            $manager->persist($task);
        }

        $manager->flush();
    }
}
