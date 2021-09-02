<?php

namespace App\Tests\SecurityTest;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
  protected function setUp(): void
  {
    $this->client = self::createClient();
    $this->manager = static::$container->get('doctrine')->getManager();
    $this->userRepository = static::$container->get(UserRepository::class);
  }

  public function loginWithAdmin(): void
  {
    $testUser = $this->userRepository->findOneByEmail('admin@test.fr');
    $this->client->loginUser($testUser);
  }

  public function testLoginWhenLogin() :void
  {
    $this->loginWithAdmin();
    $this->client->request('GET', '/login');
    $this->assertResponseStatusCodeSame(302);
  }

  public function createUser():void
  {
    $this->loginWithAdmin();
    $crawler = $this->client->request('GET', '/users/create');
    $this->assertResponseIsSuccessful();

    $this->assertSelectorTextContains('h1', 'Créer un utilisateur');

    $form = $crawler->selectButton('Ajouter')->form();
    $form['user[email]'] = 'admin3@gmail.com';
    $form['user[roles]'] = 'ROLE_ADMIN';
    $form['user[pseudo]'] = 'admin3';
    $form['user[password][first]'] = 'admin3';
    $form['user[password][second]'] = 'admin3';

    $this->client->submit($form);
  }

  public function testLogin() :void
  {
    $this->createUser();
    $crawler = $this->client->request('GET', '/logout');
    $crawler = $this->client->request('GET', '/login');
    $this->assertResponseIsSuccessful();
    $this->assertSelectorTextContains('label', 'Email :');

    $form = $crawler->selectButton('Se connecter')->form();
    $form['_username'] = "admin3@gmail.com";
    $form['_password'] = "admin3";
    $this->client->submit($form);

    $this->assertResponseStatusCodeSame(302);
    $crawler = $this->client->followRedirect();

    $this->assertResponseIsSuccessful();
    $this->assertSelectorTextContains('h1', "Bienvenue sur Todo List, l'application vous permettant de gérer l'ensemble de vos tâches sans effort !");

    $user = $this->userRepository->findOneBy(['email' => 'admin3@gmail.com']);
    $this->manager->remove($user);
    $this->manager->flush();
  }

  public function testLogout() :void
  {
    $this->loginWithAdmin();

    $this->client->request('GET', '/logout');
    $this->client->request('GET', '/users');
    $this->assertResponseStatusCodeSame(302);

  }
}
