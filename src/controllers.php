<?php

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormError;

$app->match('/', function() use ($app) {
    return $app['twig']->render('layout.html.twig');
})->bind('homepage');

$app->match('/login', function() use ($app) {

    $constraint = new Assert\Collection(array(
        'email'         => array(
            new Assert\NotBlank(),
            new Assert\Email(),
        ),
        'password'  => new Assert\NotBlank(),
    ));

    $datas = array();

    $builder = $app['form.factory']->createBuilder('form', $datas, array('validation_constraint' => $constraint));

    $form = $builder
        ->add('email', 'email', array('label' => 'Email'))
        ->add('password', 'password', array('label' => 'Password'))
        ->getForm()
    ;

    if ('POST' === $app['request']->getMethod()) {
        $form->bindRequest($app['request']);

        if ($form->isValid()) {

            $email = $form->get('email')->getData();
            $password = $form->get('password')->getData();

            if ('email@exemple.com' == $email && 'password' == $password) {
                $app['session']->set('user', array(
                  'email' => $email,
                ));

                return $app->redirect($app['url_generator']->generate('homepage'));
            }

            $form->addError(new FormError('Email / password does not match (email@exemple.com / password)'));
        }
    }

    return $app['twig']->render('form.html.twig', array('form' => $form->createView(), 'form_name' => 'Login'));
})->bind('login');

$app->match('/logout', function() use ($app) {
    $app['session']->clear();

    return $app->redirect($app['url_generator']->generate('homepage'));
})->bind('logout');

return $app;
