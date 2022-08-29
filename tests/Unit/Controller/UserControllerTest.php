<?php

namespace Tests\Unit\Controller;

use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    use RefreshDatabaseTrait;

    public function testGetCreate()
    {
        $client = static::createClient();
        $client->request('GET', '/users/create');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Créer un utilisateur');
    }

    public function testCreateAction()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/users/create');
        $form = $crawler->selectButton('Ajouter')->form([
            'user[username]' => 'Guillaume Test',
            'user[password][first]' => 'Guillaume Test',
            'user[password][second]' => 'Guillaume Test',
            'user[email]' => 'guitest@mail.com'
        ]);
        $client->submit($form);
        $this->assertResponseRedirects('/users');
        $client->followRedirect();
    }
}