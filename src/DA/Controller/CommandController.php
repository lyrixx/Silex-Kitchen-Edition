<?php
/**
 * User: Jesse
 * Date: 5/20/13
 * Time: 7:08 PM
 */

namespace DA\Controller;

use Symfony\Component\HttpFoundation\Request;
use Silex\Application;

class CommandController
{
    public function runcmd(Request $request, Application $app)
    {
        $command = $request->get('cmd');
        echo $command;
        $page_data = array();

        $form = $app['form.factory']->createBuilder('form')
            ->add('username', 'text', array('label' => 'Username', 'data' => $app['session']->get('_security.last_username')))
            ->add('password', 'password', array('label' => 'Password'))
            ->getForm();


        $page_data['error'] = false;
        $page_data['form'] = $form->createView();
        return $app['twig']->render('command.html.twig',$page_data);
    }
}