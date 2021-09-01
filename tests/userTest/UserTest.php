<?php

namespace App\Tests;

use App\Entity\Task;
use App\Entity\User;
use App\Repository\UserRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase
{
    private function getDatabaseUser(): User
    {
        self::bootKernel();
        $userRepository = static::$container->get(UserRepository::class);
        $basicUser = $userRepository->findOneByEmail('user0@todo.com');

        return $basicUser;
    }

    public function getEntityUser(): User
    {
        return (new User())
            ->setEmail("test@test.com")
            ->setRoles([])
            ->setPassword('password');
    }

    public function testGetId(){
        $user = $this->getDatabaseUser();
        $this->assertIsInt($user->getId());
    }

    public function testValidUser(){
        self::bootKernel();
        $error = self::$container->get('validator')->validate($this->getEntityUser());
        $this->assertCount(0, $error);
    }

    public function testInvalidEmail(){
        self::bootKernel();
        $error = self::$container->get('validator')->validate($this->getEntityUser()->setEmail('testblou.fr'));
        $this->assertCount(1, $error);

    }

    public function testValidEmail(){
        self::bootKernel();
        $emailToTest = $this->getEntityUser()->getEmail();
        $this->assertSame($emailToTest, filter_var($emailToTest, FILTER_VALIDATE_EMAIL));
    }

    public function testInvalidPassword(){
        self::bootKernel();
        $error = self::$container->get('validator')->validate($this->getEntityUser()->setPassword('te'));
        $this->assertCount(1, $error);
    }

    public function testValidPassword(){
        self::bootKernel();
        $error = self::$container->get('validator')->validate($this->getEntityUser()->setPassword('teff'));
        $this->assertCount(0, $error);
    }

    public function testValidRoles(){
        self::bootKernel();
        $this->assertSame(['ROLE_USER'], $this->getEntityUser()->getRoles());
    }

    public function testAddTask(){
        self::bootKernel();
        $task = new Task();
        $error = self::$container->get('validator')->validate($this->getEntityUser()->addTask($task));
        $this->assertCount(0, $error);
    }

    public function testGetTask(){
        self::bootKernel();
        $task = new Task();
        $task->setTitle("titre");
        $task->setContent("je suis un contenu");
        $user = $this->getEntityUser()->addTask($task);
        
        $getTask = $user->getTasks()[0];
        $this->assertEquals("titre", $getTask->getTitle());
        $this->assertEquals("je suis un contenu", $getTask->getContent());
    }

    public function testDeleteTask(){
        self::bootKernel();
        $user = $this->getDatabaseUser();
        $tasksSize = $user->getTasks()->count();
        $user->removeTask($user->getTasks()[0]);
        $this->assertCount($tasksSize-1, $user->getTasks());
    }
}
