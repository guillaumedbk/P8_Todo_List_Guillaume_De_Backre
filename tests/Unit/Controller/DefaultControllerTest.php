<?php

namespace Tests\Unit\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private ?UserRepository $userRepository;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->userRepository = static::$container->get(UserRepository::class);
    }

    public function testRestrictedIndex()
    {
        $this->client->request('GET', '/');
        $this->assertResponseRedirects('http://localhost/login');
    }

    public function testIndex()
    {
        $testUser = $this->userRepository->findOneBy(['email' => 'guitest@mail.com']);
        $this->client->loginUser($testUser);

        $this->client->request('GET', '/');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Bienvenue sur Todo List');
    }
}
