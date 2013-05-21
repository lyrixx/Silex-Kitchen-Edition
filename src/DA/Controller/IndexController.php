<?php

namespace DA\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Storage\MetadataBag;
use Symfony\Component\Validator\Constraints as Assert;
use Silex\Application;;
use DA\Model\Entity\ModuleEntity;

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
        $page_data['configuration'] = array($app['db']->fetchAll('SELECT * FROM core_module'));

        //$core_page = $app['db.orm']->find('DA\Model\Entity\ModuleEntity', 1);

        //var_dump($core_page);exit;
        //var_dump($app['db.orm.helper_set']);exit;

        //var_dump($app['db.orm.em']);exit;

		return $app['twig']->render('doctrine.html.twig',$page_data);
	}
}

