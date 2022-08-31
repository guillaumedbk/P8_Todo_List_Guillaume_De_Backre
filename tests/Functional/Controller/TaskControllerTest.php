<?php

namespace Tests\Functional\Controller;

use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class TaskControllerTest extends WebTestCase
{
    use RefreshDatabaseTrait;

    private KernelBrowser $client;
    private ?UserRepository $userRepository;
    private ?TaskRepository $taskRepository;
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->userRepository = static::$container->get(UserRepository::class);
        $this->taskRepository = static::$container->get(TaskRepository::class);
        $this->entityManager = $this->getContainer()->get('doctrine.orm.default_entity_manager');
    }

    public function testListActionNotAuthenticatedUser()
    {
        $this->client->request('GET', '/tasks');
        $this->assertResponseRedirects('http://localhost/login');
    }

    public function testListActionAuthenticatedUser()
    {
        $testUser = $this->userRepository->findOneBy(['email' => 'guitest@mail.com']);
        $this->client->loginUser($testUser);

        $this->client->request('GET', '/tasks');
        $this->assertResponseIsSuccessful();
    }

    public function testCreateAction()
    {
        $testUser = $this->userRepository->findOneBy(['email' => 'guitest@mail.com']);
        $this->client->loginUser($testUser);
        $crawler = $this->client->request('GET', '/tasks/create');
        $form = $crawler->selectButton('Ajouter')->form([
            'task[title]' => 'test title',
            'task[content]' => 'Test content task'
        ]);
        $this->client->submit($form);
        $this->assertResponseRedirects('/tasks');
        $this->client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success', 'Superbe ! La tâche a été bien été ajoutée.');
    }

    public function testEditAction()
    {
        $testUser = $this->userRepository->findOneBy(['email' => 'anonyme@mail.com']);
        $this->client->loginUser($testUser);

        $crawler = $this->client->request('GET', '/tasks/1/edit');
        $form = $crawler->selectButton('Modifier')->form([
            'task[title]' => 'modified title',
            'task[content]' => 'Test modified content task'
        ]);
        $this->client->submit($form);
        $this->assertResponseRedirects('/tasks');
        $this->client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success', 'La tâche a bien été modifiée.');
    }

    public function testAutorisationEditAction()
    {
        $testUser = $this->userRepository->findOneBy(['email' => 'guitest@mail.com']);
        $this->client->loginUser($testUser);

        $crawler = $this->client->request('GET', '/tasks/1/edit');
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testToggleTaskAction()
    {
        $testUser = $this->userRepository->findOneBy(['email' => 'anonyme@mail.com']);
        $this->client->loginUser($testUser);
        $task = $this->taskRepository->findOneBy(['title' => 'test title']);
        $this->client->request('GET', '/tasks/1/toggle');
        $task->toggle(!$task->isDone());
        $this->entityManager->flush($task);
        $this->assertResponseRedirects('/tasks');
        $this->client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');
    }

    public function testUnAuthorisedDeleteAction()
    {
        $testUser = $this->userRepository->findOneBy(['email' => 'guitest@mail.com']);
        $this->client->loginUser($testUser);
        $task = $this->taskRepository->findOneBy(['title' => 'test title']);
        $this->client->request('GET', '/tasks/1/delete');
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testDeleteAction()
    {
        $testUser = $this->userRepository->findOneBy(['email' => 'anonyme@mail.com']);
        $this->client->loginUser($testUser);
        $task = $this->taskRepository->findOneBy(['title' => 'test title']);
        $this->client->request('GET', '/tasks/1/delete');
        $this->entityManager->remove($task);
        $this->entityManager->flush();
        $this->assertResponseRedirects('/tasks');
        $this->client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success', 'La tâche a bien été supprimée.');
    }
}
