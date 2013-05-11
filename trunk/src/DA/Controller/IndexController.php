<?php

namespace DA\Controller;

use Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
use Silex\Application;

class IndexController
{
	public function index(Request $request,Application $app)
	{
		$app['session']->getFlashBag()->add('warning', 'Warning flash message');
		$app['session']->getFlashBag()->add('info', 'Info flash message');
		$app['session']->getFlashBag()->add('success', 'Success flash message');
		$app['session']->getFlashBag()->add('error', 'Error flash message');

		return $app['twig']->render('index.html.twig');
	}

	public function doctrine(Request $request,Application $app)
	{
		return $app['twig']->render(
		'doctrine.html.twig',
		array(
		    'posts' => $app['db']->fetchAll('SELECT * FROM post')
		)
		);
	}
}

