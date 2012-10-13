<?php

use Silex\WebTestCase;
use Symfony\Component\HttpFoundation\Session\Storage\MockFileSessionStorage;

class ApplicationTest extends WebTestCase
{
    public function createApplication()
    {
        // Silex
        $app = new Silex\Application();
        require __DIR__.'/../../resources/config/test.php';
        require __DIR__.'/../../src/app.php';

        // Use FilesystemSessionStorage to store session
        $app['session.storage'] = $app->share(function() {
            return new MockFileSessionStorage(sys_get_temp_dir());
        });

        // Controllers
        require __DIR__ . '/../../src/controllers.php';

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
        $crawler = $client->request('GET', '/login');

        $this->assertTrue($client->getResponse()->isOk());

        $form = $crawler->selectButton('Send')->form(array());
        $crawler = $client->submit($form, array());

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
            'form[password]'  => 'wrong',
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
