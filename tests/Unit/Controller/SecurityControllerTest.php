<?php

namespace Tests\Unit\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\HttpFoundation\Session\Storage\MockFileSessionStorage;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class SecurityControllerTest extends WebTestCase
{
    private ?UserRepository $userRepository;

    public function testGetLogin()
    {
        $client = static::createClient();
        $client->request('GET', '/login');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorNotExists('.alert.alert-danger');
    }

    public function testLoginSuccessFull()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'Guillaume Test',
            '_password' => 'Guillaume'
        ]);
        $client->submit($form);

        $this->assertResponseRedirects('http://localhost/');
        $client->followRedirect();
        $this->assertSelectorTextContains('h1', 'Bienvenue sur Todo List');
    }

    public function testLoginWithBadCredentials()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'Inconnu',
            '_password' => 'fakepassword'
        ]);
        $client->submit($form);
        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-danger');
    }
}
