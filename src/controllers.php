<?php

namespace Oclane;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Validator\Constraints as Assert;

//use Oclane\Interpreter;
//use Oclane\Snippet;

/*--------------------------------------------------------------------*
 * home (php)
 *--------------------------------------------------------------------*/
$app->match('/', function() use ($app) {
    $snippet = new Snippet($app['db']);
    list($options,$snippets) = $snippet->getOptionsList();

    $resultat='';
    $form = $app['form.factory']->createBuilder('form')
        ->add('code', 'textarea', array(
                'label'      => 'Code',
                'attr' => array('rows'=>10,'style'=>'width:100%')
        ))
        ->add('pre','checkbox',array(
            'label' => 'Pre-formatted result'
        ))
        ->add('name','text')
        ->add('snippet', 'choice',  array(
            'choices'  => $options,
            'multiple' => false,
            'expanded' => false
        ))
        ->getForm()
    ;

    if ('POST' === $app['request']->getMethod()) {
        $form->bindRequest($app['request']);

        if ($form->isValid()) {
            $interp = new Interpreter($app);
            $pre = $form->get('pre')->getData();
            $code = $form->get('code')->getData();
            $save = array_key_exists('save',$_POST);// && $_POST['save'] == 'save';
            $test = array_key_exists('test',$_POST);// && $_POST['test'] == 'test';
            if ($test) {
                $resultat = $interp->evalPhp($code);
            } elseif ($save) {
                $name = $form->get('name')->getData();
                if (!empty($name) && !empty($code)) {
                    $snippet->add($name,$code);
                    $resultat="snippet '$name' saved";
                    $app['session']->setFlash('success', $resultat);

                    return $app->redirect($app['url_generator']->generate('homepage'));
                } else {
                    $app['session']->setFlash('error', "Can't save without 'name' and 'code' !!");
                }
            }
        }
    }
    if (empty($resultat)) {
        $resultat = '<h2>Welcome to PHPLive !</h2>';
        $pre = false;
    }
    if ($pre) {
        $resultat = "<pre>\n$resultat\n</pre>";
    }

    $bloc_resultat = "\n<div class=\"result\">$resultat</div>\n";

    return $app['twig']->render('index.html.twig',array(
        'page_title' => 'Versatile interpretor, PHP mode',
        'form' => $form->createView(),
        'snippets' => $snippets,
        'bloc_resultat' => $bloc_resultat,
        )
    );
})->bind('homepage');

/*--------------------------------------------------------------------*
 * javascript
 *--------------------------------------------------------------------*/
$app->match('/javascript', function() use ($app) {
    $snippet = new SnippetJs($app['db']);
    list($options,$snippets) = $snippet->getOptionsList();

    $resultat='';
    $form = $app['form.factory']->createBuilder('form')
        ->add('code', 'textarea', array(
                'label'      => 'Code',
                'attr' => array('rows'=>10,'style'=>'width:100%')
        ))
        ->add('name','text')
        ->add('snippet', 'choice',  array(
            'choices'  => $options,
            'multiple' => false,
            'expanded' => false
        ))
        ->getForm()
    ;

    if ('POST' === $app['request']->getMethod()) {
        $form->bindRequest($app['request']);

        if ($form->isValid()) {
            $interp = new Interpreter($app);
            $code = $form->get('code')->getData();
            $save = array_key_exists('save',$_POST);// && $_POST['save'] == 'save';
            $test = array_key_exists('test',$_POST);// && $_POST['test'] == 'test';
            if ($test) {
                $resultat = $interp->evalJs($code);
            } elseif ($save) {
                $name = $form->get('name')->getData();
                if (!empty($name) && !empty($code)) {
                    $snippet->add($name,$code);
                    $resultat="snippet '$name' saved";
                    $app['session']->setFlash('success', $resultat);

                    return $app->redirect($app['url_generator']->generate('jscript'));
                } else {
                    $app['session']->setFlash('error', "Can't save without 'name' and 'code' !!");
                }
            }
         }
    }
    if (empty($resultat)) {
        $resultat = '
            <script type="text/javascript">
                document.write("<h2>Welcome to PHPLive !</h2>");
            </script>';
    }
    $bloc_resultat = "\n<div class=\"result\">$resultat</div>\n";

    return $app['twig']->render('index.html.twig',array(
        'active' => 'jscript',
        'page_title' => 'Versatile interpretor, Javascript mode',
        'form' => $form->createView(),
        'snippets' => $snippets,
        'bloc_resultat' => $bloc_resultat,
        )
    );
})->bind('jscript');

/*--------------------------------------------------------------------*
 * SQL
 *--------------------------------------------------------------------*/
$app->match('/sql', function() use ($app) {
    $snippet = new SnippetSql($app['db']);
    list($options,$snippets) = $snippet->getOptionsList();

    $resultat='';
    $form = $app['form.factory']->createBuilder('form')
        ->add('code', 'textarea', array(
                'label'      => 'Code',
                'attr' => array('rows'=>10,'style'=>'width:100%')
        ))
        ->add('name','text')
        ->add('snippet', 'choice',  array(
            'choices'  => $options,
            'multiple' => false,
            'expanded' => false
        ))
        ->getForm()
    ;

    if ('POST' === $app['request']->getMethod()) {
        $form->bindRequest($app['request']);

        if ($form->isValid()) {
            $interp = new Interpreter($app);
            $code = $form->get('code')->getData();
            $save = array_key_exists('save',$_POST);// && $_POST['save'] == 'save';
            $test = array_key_exists('test',$_POST);// && $_POST['test'] == 'test';
            if ($test) {
                $resultat = $interp->evalSql($code);
            } elseif ($save) {
                $name = $form->get('name')->getData();
                if (!empty($name) && !empty($code)) {
                    $snippet->add($name,$code);
                    $resultat="snippet '$name' saved";
                    $app['session']->setFlash('success', $resultat);

                    return $app->redirect($app['url_generator']->generate('sql'));
                } else {
                    $app['session']->setFlash('error', "Can't save without 'name' and 'code' !!");
                }
            }
         }
    }
    if (empty($resultat)) {
        $resultat = 'No default result for SQL...';
    }
    $bloc_resultat = "\n<div class=\"result\">$resultat</div>\n";

    return $app['twig']->render('index.html.twig',array(
        'active' => 'sql',
        'page_title' => 'Versatile interpretor, SQL mode',
        'form' => $form->createView(),
        'snippets' => $snippets,
        'bloc_resultat' => $bloc_resultat,
        )
    );
})->bind('sql');

$app->match('/encode', function() use ($app) {
    $form = $app['form.factory']->createBuilder('form')
        ->add('password', 'text', array(
            'label'       => 'Password',
            'constraints' => array(
                new Assert\NotBlank(),
            ),
        ))
        ->getForm()
    ;

    if ('POST' === $app['request']->getMethod()) {
        $form->bindRequest($app['request']);

        if ($form->isValid()) {

            $password = $form->get('password')->getData();
            $encoded = $app['security.encoder.digest']->encodePassword($password, '');
            $app['session']->setFlash('success', 'Encoded: ' . $encoded);
        }
    }

    return $app['twig']->render('encode.html.twig', array('form' => $form->createView()));
})
->bind('encode');

$app->match('/login', function() use ($app) {
    $request = $app['request'];

    return $app['twig']->render('login.html.twig', array(
        'error' => $app['security.last_error']($request),
        'last_username' => $app['session']->get('_security.last_username'),
    ));
})
->bind('login');

$app->match('/logout', function() use ($app) {
    $app['session']->clear();

    return $app->redirect($app['url_generator']->generate('homepage'));
})->bind('logout');

$app->get('/phpinfo', function() use ($app) {

    ob_start();
    phpinfo();
    $info = ob_get_contents();
    ob_end_clean();
    $url = $app['url_generator']->generate('homepage');
    $start='<body><div style="padding:4px">' .
    '<a href="' . $url . '">Back</a></div>';
    //$info = str_replace('<body>',$start,$info);
    $parts = explode('<body>',$info);
    $parts = explode('</body>',$parts[1]);
    $info = preg_replace('#,\b#',', ',$parts[0]);

    return $app['twig']->render('phpinfo.html.twig', array(
        'info' => $info,
        )
    );

})
->bind('phpinfo');

$app->get('/doc', function() use ($app) {
    return $app['twig']->render('doc.html.twig');

})
->bind('doc');

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
