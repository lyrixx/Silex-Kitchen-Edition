<?php

namespace DA\Controller;

use Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
use Silex\Application;

class ComponentController
{
    public function index(Request $request,Application $app)
    {
        return $app['twig']->render('components.html.twig',array());
	}
}

