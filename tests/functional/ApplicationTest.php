<?php

use App\Application;
use Silex\WebTestCase;

class ApplicationTest extends WebTestCase
{
    public function createApplication()
    {
        // Silex
        $app = new Application('test');
        $app['session.test'] = true;

        return $app;
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

        $form = $crawler->selectButton('Submit')->form(array());
        $crawler = $client->submit($form, array());
        $this->assertEquals(1, $crawler->filter('.alert-danger')->count());

        $form = $crawler->selectButton('Submit')->form();
        $crawler = $client->submit($form, array(
            '_username' => 'wrong username',
            '_password' => 'wrong password',
        ));
        $this->assertEquals(1, $crawler->filter('.alert-danger')->count());

        $form = $crawler->selectButton('Submit')->form();
        $crawler = $client->submit($form, array(
            '_username' => 'alice',
            '_password' => 'password',
        ));
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
        $this->assertEquals(1, $crawler->filter('.alert-danger')->count());
    }

    public function testPageCache()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/cache');
        $this->assertRegExp('#This page is cached#', $crawler->filter('body')->text());
    }

    public function testLoggingWithPsrInterface()
    {
        $msg = 'Check the logger';
        $this->app['logger']->error($msg);
        $this->assertStringEndsWith("app.ERROR: $msg [] []\n", file_get_contents(__DIR__.'/../../var/logs/app.log'));
    }
}
