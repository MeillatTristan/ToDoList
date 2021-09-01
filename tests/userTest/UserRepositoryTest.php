<?php

namespace App\Tests;

use App\DataFixtures\UserFixtures;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;

class UserRepositoryTest extends KernelTestCase
{

    public function testCount(): void
    {
        $kernel = self::bootKernel();
        $users = self::$container->get(UserRepository::class)->count([]);

        $this->assertEquals(11, $users);
        //$routerService = self::$container->get('router');
        //$myCustomService = self::$container->get(CustomService::class);
    }
}
