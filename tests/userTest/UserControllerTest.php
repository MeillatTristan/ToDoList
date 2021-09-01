<?php

namespace App\Tests\UserTest;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends WebTestCase
{
    protected function setUp(): void
    {
        $this->client = self::createClient();
        $this->userRepository = static::$container->get(UserRepository::class);
        $this->manager = static::$container->get('doctrine')->getManager();
    }

    public function loginWithAdmin(): void
    {
        $testUser = $this->userRepository->findOneByEmail('admin@test.fr');
        $this->client->loginUser($testUser);
        
    }

    public function loginWithUser(): void
    {
        $testUser = $this->userRepository->findOneByEmail('user0@todo.com');
        $this->client->loginUser($testUser);
    }

    public function testAccessIsGranted(): void
    {
        $this->client->request('GET', '/users');

        // if user is not admin redirect to login
        $this->assertResponseStatusCodeSame(302);
    }

    public function testAccessAdmin(): void
    {
        $this->loginWithAdmin();
        $this->client->request('GET', '/users');
        $this->assertResponseIsSuccessful();
    }

    public function testCreateAction()
    {
        $this->loginWithAdmin();
        $crawler = $this->client->request('GET', '/users/create');
        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('h1', 'Créer un utilisateur');

        $form = $crawler->selectButton('Ajouter')->form();
        $form['user[email]'] = 'admin2@gmail.com';
        $form['user[roles]'] = 'ROLE_ADMIN';
        $form['user[pseudo]'] = 'admin2';
        $form['user[password][first]'] = 'admin2';
        $form['user[password][second]'] = 'admin2';

        $this->client->submit($form);

        $user = $this->userRepository->findOneBy(['email' => 'admin2@gmail.com']);
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('admin2@gmail.com', $user->getEmail());
        $this->assertEquals('ROLE_ADMIN', $user->getRoles()[0]);

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertEquals(1, $crawler->filter('div.alert-success')->count());

    }

    public function testEditAction()
    {
        $this->loginWithAdmin();
        $user = $this->userRepository->findOneBy(['email' => 'admin2@gmail.com']);
        $userID = $user->getID();
        $crawler = $this->client->request('GET', "/users/$userID/edit");
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Modifier')->form();
        $form['user[email]'] = 'edit@test.fr';
        $form['user[pseudo]'] = 'admin2';
        $form['user[password][first]'] = 'admin2';
        $form['user[password][second]'] = 'admin2';
        $this->client->submit($form);

        $user = $this->userRepository->findOneBy(['email' => 'edit@test.fr']);
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('edit@test.fr', $user->getEmail());

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertEquals(1, $crawler->filter('div.alert-success')->count());

        $this->manager->remove($user);
        $this->manager->flush();
    }
}
