<?php

namespace Tests\Unit\Controller;

use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends WebTestCase
{
    use RefreshDatabaseTrait;
    private KernelBrowser $client;
    private ?UserRepository $userRepository;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->userRepository = static::$container->get(UserRepository::class);
    }

    public function testGetList()
    {
        $testUser = $this->userRepository->findOneBy(['email' => 'anonyme@mail.com']);
        $this->client->loginUser($testUser);
        $this->client->request('GET', '/users');
        $this->assertResponseIsSuccessful();
    }

    public function testAutorisationGetList()
    {
        $user = $this->userRepository->findOneBy(['email' => 'guitest@mail.com']);
        $this->client->loginUser($user);
        $this->client->request('GET', '/users');
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testGetCreate()
    {
        $this->client->request('GET', '/users/create');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Créer un utilisateur');
    }

    public function testCreateAction()
    {
        $crawler = $this->client->request('GET', '/users/create');
        $form = $crawler->selectButton('Ajouter')->form([
            'user[username]' => 'Test',
            'user[password][first]' => 'Test',
            'user[password][second]' => 'Test',
            'user[email]' => 'test@mail.com',
        ]);
        $this->client->submit($form);
        $this->assertResponseRedirects('/users');
        $this->client->followRedirect();
    }

    public function testEditAction()
    {
        $testUser = $this->userRepository->findOneBy(['email' => 'anonyme@mail.com']);
        $this->client->loginUser($testUser);

        $crawler = $this->client->request('GET', '/users/3/edit');
        $form = $crawler->selectButton('Modifier')->form([
            'user[username]' => 'Test Modifié',
            'user[password][first]' => 'Test',
            'user[password][second]' => 'Test',
            'user[email]' => 'test@mail.com',
        ]);
        $this->client->submit($form);
        $this->assertResponseRedirects('/users');
        $this->client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success', 'L\'utilisateur a bien été modifié');
    }
}