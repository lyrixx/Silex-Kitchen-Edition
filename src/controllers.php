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

            if ('email@example.com' == $email && 'password' == $password) {
                $app['session']->set('user', array(
                    'email' => $email,
                ));

                $app['session']->setFlash('notice', 'You are now connected');

                return $app->redirect($app['url_generator']->generate('homepage'));
            }

            $form->addError(new FormError('Email / password does not match (email@example.com / password)'));
        }
    }

    return $app['twig']->render('form.html.twig', array('form' => $form->createView(), 'form_name' => 'Login'));
})->bind('login');

$app->match('/logout', function() use ($app) {
    $app['session']->clear();

    return $app->redirect($app['url_generator']->generate('homepage'));
})->bind('logout');

$app->get('/page-with-cache', function() use ($app) {
    $response = new Response($app['twig']->render('page-with-cache.html.twig', array('date' => date('Y-M-d h:i:s'))));
    $response->setCache(array(
        'max_age'       => 10,
        's_maxage'      => 10,
    ));

    return $response;
})->bind('page_with_cache');

$app->error(function (\Exception $e, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    switch ($code) {
        case 404:
            $message = 'The requested page could not be found.';
            break;
        default:
            $message = 'We are sorry, but something went terribly wrong.';
    }

    return new Response($message, $code);
});

return $app;
