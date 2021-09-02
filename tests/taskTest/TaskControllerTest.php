<?php

namespace App\Tests\TaskTest;

use App\Entity\Task;
use App\Entity\User;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
  protected function setUp(): void
  {
    $this->client = self::createClient();
    $this->userRepository = static::$container->get(UserRepository::class);
    $this->taskRepository = static::$container->get(TaskRepository::class);
    $this->manager = static::$container->get('doctrine')->getManager();
  }

  public function loginWithAdmin(): void
  {
    $testUser = $this->userRepository->findOneByEmail('admin@test.fr');
    $this->client->loginUser($testUser);
  }

  public function testAccessIsGranted(): void
  {
    $this->client->request('GET', '/tasks');
    $this->assertResponseStatusCodeSame(302);
  }

  public function testShowAllTask(): void
  {
    $this->loginWithAdmin();
    $this->client->request('GET', '/tasks');
    $this->assertResponseIsSuccessful();
  }

  public function testCreateTask() :void
  {
    $this->loginWithAdmin();
    $crawler = $this->client->request('GET', '/tasks/create');
    $this->assertResponseIsSuccessful();

    $this->assertSelectorTextContains('label', 'Title');
    $form = $crawler->selectButton('Ajouter')->form();
    $form['task[title]'] = "test title";
    $form['task[content]'] = "test content";

    $this->client->submit($form);

    $task = $this->taskRepository->findOneBy(['title' => 'test title']);
    $this->assertInstanceOf(Task::class, $task);
    $this->assertEquals('test title', $task->getTitle());
    $this->assertEquals('test content', $task->getContent());

    $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

    $crawler = $this->client->followRedirect();

    $this->assertResponseIsSuccessful();
    $this->assertEquals(1, $crawler->filter('div.alert-success')->count());
  }

  public function testToggleTask() :void
  {
    $this->loginWithAdmin();

    $task = $this->taskRepository->findOneBy(['title' => 'test title']);
    $this->assertSame(false, $task->isDone());
    $taskID = $task->getId();

    $this->client->request('GET', "/tasks/$taskID/toggle");
    $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

    $this->assertSame(true, $task->isDone());
  }

  public function testEditTask() :void
  {
    $this->loginWithAdmin();
    $task = $this->taskRepository->findOneBy(['title' => 'test title']);
    $taskID = $task->getId();
    $crawler = $this->client->request('GET', "/tasks/$taskID/edit");
    $this->assertResponseIsSuccessful();

    $form = $crawler->selectButton('Modifier')->form();
    $form['task[title]'] = "edit title";
    $form['task[content]'] = "edit content";
    $this->client->submit($form);

    $task = $this->taskRepository->findOneBy(['title' => 'edit title']);
    $this->assertInstanceOf(Task::class, $task);
    $this->assertSame('edit title', $task->getTitle());
    $this->assertSame('edit content', $task->getContent());
    $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

    $crawler = $this->client->followRedirect();

    $this->assertResponseIsSuccessful();
    $this->assertEquals(1, $crawler->filter('div.alert-success')->count());
  }

  public function testBadUserDeleteTask() :void
  {
    $testUser = $this->userRepository->findOneByEmail('admin2@test.fr');
    $this->client->loginUser($testUser);

    $taskB = $this->taskRepository->findOneBy(['title' => 'edit title']);
    $this->assertInstanceOf(Task::class, $taskB);

    $taskID = $taskB->getId();
    $this->client->request('GET', "/tasks/$taskID/delete");

    $task = $this->taskRepository->findOneBy(['title' => 'edit title']);
    $this->assertSame($taskB, $task);

    $crawler = $this->client->followRedirect();

    $this->assertResponseIsSuccessful();
    $this->assertEquals(1, $crawler->filter('div.alert-danger')->count());
  }

  public function testDeleteTaskAnonyme() :void
  {
    $user = new User;
    $user->setPseudo('anonyme');
    $user->setEmail('anonyme@test.fr');
    $user->setPassword('admin123');

    $task = new Task;
    $task->setContent("test anonyme");
    $task->setTitle("test title anonyme");
    $task->setUser($user);
    $this->manager->persist($task);
    $this->manager->flush();

    $this->loginWithAdmin();

    $task = $this->taskRepository->findOneBy(['title' => 'test title anonyme']);
    $this->assertInstanceOf(Task::class, $task);

    $taskID = $task->getId();
    $this->client->request('GET', "/tasks/$taskID/delete");
    $this->manager->remove($task->getUser());
    $this->manager->flush();

    $task = $this->taskRepository->findOneBy(['title' => 'test anonyme']);
    $this->assertSame(null, $task);

    $crawler = $this->client->followRedirect();

    $this->assertResponseIsSuccessful();
    $this->assertEquals(1, $crawler->filter('div.alert-success')->count());
  }

  public function testDeleteTask() :void
  {
    $this->loginWithAdmin();

    $task = $this->taskRepository->findOneBy(['title' => 'edit title']);
    $this->assertInstanceOf(Task::class, $task);

    $taskID = $task->getId();
    $this->client->request('GET', "/tasks/$taskID/delete");

    $task = $this->taskRepository->findOneBy(['title' => 'edit title']);
    $this->assertSame(null, $task);

    $crawler = $this->client->followRedirect();

    $this->assertResponseIsSuccessful();
    $this->assertEquals(1, $crawler->filter('div.alert-success')->count());
  }
}
