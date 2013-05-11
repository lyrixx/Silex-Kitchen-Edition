<?php

namespace DA\Controller;

use Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
use Silex\Application;

class LoginController
{
	public function index(Request $request,Application $app)
	{
		$form = $app['form.factory']->createBuilder('form')
        ->add('username', 'text', array('label' => 'Username', 'data' => $app['session']->get('_security.last_username')))
        ->add('password', 'password', array('label' => 'Password'))
        ->getForm();

		return $app['twig']->render('login.html.twig', array(
		    'form'  => $form->createView(),
		    'error' => $app['security.last_error']($request),
		));
	}
}
