<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    protected function setUp(): void
    {
        $this->client = self::createClient();
        $this->userRepository = static::$container->get(UserRepository::class);
    }
    public function testHomepageAsAnonyme()
    {
        $this->client->request('GET', '/');

        $this->assertResponseStatusCodeSame(302);
    }

    public function testHomepageAsAuth() :void
    {
        $testUser = $this->userRepository->findOneByEmail('admin@test.fr');
        $this->client->loginUser($testUser);
        $this->client->request('GET', '/');
        $this->assertSelectorTextContains('h1', "Bienvenue sur Todo List, l'application vous permettant de gérer l'ensemble de vos tâches sans effort !");
    }
}