<?php

use Silex\WebTestCase;
use Symfony\Component\HttpFoundation\SessionStorage\FilesystemSessionStorage;
use Silex\Provider\DoctrineServiceProvider;

class ApplicationTest extends WebTestCase
{
    public function createApplication()
    {
        // Silex
        $this->app = require __DIR__.'/../../src/app.php';

        // Tests mode
        $this->app['debug'] = true;
        unset($this->app['exception_handler']);
        $app['translator.messages'] = array();

        // Use FilesystemSessionStorage to store session
        $this->app['session.storage'] = $this->app->share(function() {
            return new FilesystemSessionStorage(sys_get_temp_dir());
        });

        // Controllers
        require __DIR__ . '/../../src/controllers.php';

        return $app;
    }

    public function test404()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/give-me-a-404');

        $this->assertTrue($client->getResponse()->isNotFound());
    }

    public function testLogin()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/login');

        $this->assertTrue($client->getResponse()->isOk());

        $form = $crawler->selectButton('Send')->form(array());
        $crawler = $client->submit($form);
        $this->assertEquals(2, $crawler->filter('.error')->count());

        $form = $crawler->selectButton('Send')->form();
        $crawler = $client->submit($form, array(
            'form[email]'     => 'not an email',
            'form[password]'  => 'wrong password',
        ));
        $this->assertEquals(1, $crawler->filter('.error')->count());

        $form = $crawler->selectButton('Send')->form();
        $crawler = $client->submit($form, array(
            'form[email]'     => 'email@example.com',
            'form[password]'  => 'wrong password',
        ));
        $this->assertEquals(1, $crawler->filter('.error')->count());

        $form = $crawler->selectButton('Send')->form();
        $crawler = $client->submit($form, array(
            'form[email]'     => 'email@example.com',
            'form[password]'  => 'password',
        ));
        $this->assertEquals(0, $crawler->filter('.error')->count());
        $crawler = $client->followRedirect();
        $this->assertEquals(2, $crawler->filter('a[href="/logout"]')->count());
    }
}
