<?php

use Silex\WebTestCase;
use Silex\Application;
use Symfony\Component\HttpFoundation\Session\Storage\MockFileSessionStorage;

class ApplicationTest extends WebTestCase
{
    public function createApplication()
    {
        // Silex
        $app = new Application();
        require __DIR__.'/../../resources/config/test.php';
        require __DIR__.'/../../src/app.php';

        $app['session.test'] = true;


        // Controllers & routes
        require __DIR__ . '/../../src/routes.php';

        return $this->app = $app;
    }

    public function test404()
    {
        $client = $this->createClient();

        $client->request('GET', '/give-me-a-404');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testLogin()
    {
        $client = $this->createClient();
        $client->followRedirects(true);

        $crawler = $client->request('GET', '/login');

        $this->assertTrue($client->getResponse()->isOk());

        $form = $crawler->selectButton('Send')->form(array());
        $crawler = $client->submit($form, array());
        $this->assertEquals(1, $crawler->filter('.alert-error')->count());

        $form = $crawler->selectButton('Send')->form();
        $crawler = $client->submit($form, array('form' => array(
            'username' => 'wrong username',
            'password' => 'wrong password',
        )));
        $this->assertEquals(1, $crawler->filter('.alert-error')->count());

        $form = $crawler->selectButton('Send')->form();
        $crawler = $client->submit($form, array('form' => array(
            'username' => 'username',
            'password' => 'password',
        )));
        $this->assertEquals(2, $crawler->filter('a[href="/logout"]')->count());
    }
}
