<?php

use Silex\WebTestCase;
use Silex\Application;

class ApplicationTest extends WebTestCase
{
    public function createApplication()
    {
        // Silex
        $app = new Application();
        require __DIR__.'/../../resources/config/test.php';
        require __DIR__.'/../../src/app.php';

        $app['session.test'] = true;

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

    public function testFullForm()
    {
        $client = $this->createClient();
        $client->followRedirects(true);

        $crawler = $client->request('GET', '/form');
        $this->assertEquals('France', $crawler->filter('form select[id=form_country] option[value=FR]')->text());

        $form = $crawler->selectButton('Submit')->form();
        $crawler = $client->submit($form);
        $this->assertEquals(1, $crawler->filter('.alert-error')->count());
    }

    public function testPageCache()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/page-with-cache');
        $this->assertRegExp('#This page is cached#', $crawler->filter('body')->text());
    }

    public function testLoggingWithPsrInterface()
    {
        $msg = 'Check the logger';
        $this->app['logger']->error($msg);
        $this->assertStringEndsWith("app.ERROR: $msg [] []\n", file_get_contents(__DIR__.'/../../resources/log/app.log'));
    }
}
