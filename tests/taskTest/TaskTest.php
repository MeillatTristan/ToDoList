<?php

namespace App\Tests;

use App\Entity\Task;
use App\Entity\User;
use App\Repository\UserRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskTest extends KernelTestCase{

  private function getDatabaseTask(): Task
    {
        self::bootKernel();
        $userRepository = static::$container->get(TaskRepository::class);
        $basicUser = $userRepository->findOneById(1);

        return $basicUser;
    }

    public function getEntityTask(): Task
    {
        $task = new Task();
        $task->setTitle("title");
        $task->setContent("content");

        return $task;
    }

    public function testValidTask(){
      $task = $this->getEntityTask();
      self::bootKernel();
      $error = self::$container->get('validator')->validate($task);
      $this->assertCount(0, $error);
    }

    public function testValidTitleTask(){
      $task = $this->getEntityTask();
      $this->assertEquals($task->getTitle(), "title");
    }

    public function testValidContentTask(){
      $task = $this->getEntityTask();
      $this->assertEquals($task->getContent(), "content");
    }

    public function testInvalidBlankContentTask()
    {
      self::bootKernel();
      $task = $this->getEntityTask();
      $task->setContent("");
      $error = self::$container->get('validator')->validate($task);
      $this->assertCount(1, $error);
    }

    public function testInvalidBlankTitleTask()
    {
      self::bootKernel();
      $task = $this->getEntityTask();
      $task->setTitle("");
      $error = self::$container->get('validator')->validate($task);
      $this->assertCount(1, $error);
    }

    public function testAssignedAtTask()
    {
      self::bootKernel();
      $task = $this->getEntityTask();
      $task->setCreatedAt(new \DateTime());
      $error = self::$container->get('validator')->validate($task);
      $this->assertCount(0, $error);
    }

    public function testIsDoneTask()
    {
      $task = $this->getEntityTask();
      $task->toggle(true);
      $this->assertEquals(true, $task->IsDone());
    }

    public function testValidGetUserTask()
    {
      $user = new User;
      $user->setEmail("test@test.com");
      $user->setRoles([]);
      $user->setPassword('password');
      $task = $this->getEntityTask()->setUser($user);
      $this->assertEquals($task->getUser()->getEmail(), "test@test.com");
    }

    public function testGetUserLinkedAnonyme(){
      $task = new Task;
      $this->assertEquals('anonyme', $task->getUser()->getPseudo());
    }

    public function testGetCreatedAt(){
      $task = new Task;
      $date = $task->getCreatedAt()->format('Y-m-d H:i:s');
      $currentDate = new \DateTime();
      $this->assertSame($currentDate->format('Y-m-d H:i:s'), $date);
    }
}