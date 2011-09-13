<?php

require_once __DIR__ . '/../vendor/silex/autoload.php';

use Silex\Extension\SessionExtension;
use Silex\Extension\SymfonyBridgesExtension;
use Silex\Extension\ValidatorExtension;
use Silex\Extension\FormExtension;
use Silex\Extension\UrlGeneratorExtension;
use Silex\Extension\TranslationExtension;
use Silex\Extension\TwigExtension;
use Silex\Extension\DoctrineExtension;

$app = new Silex\Application();

require __DIR__ . '/config.php';

$app->register(new SessionExtension());
$app->register(new SymfonyBridgesExtension());
$app->register(new ValidatorExtension());
$app->register(new FormExtension());
$app->register(new UrlGeneratorExtension());

$app->register(new TranslationExtension(), array(
    'locale_fallback' => $app['locale'],
));
$app['translator.loader'] = new Symfony\Component\Translation\Loader\YamlFileLoader();


$app->register(new TwigExtension(), array(
    'twig.options'  => array('cache' => false, 'strict_variables' => true),
    'twig.path'     => array(
        __DIR__ . '/../views/common',
        __DIR__ . '/../views',
    )
));

$oldTwigConfiguration = isset($app['twig.configure']) ? $app['twig.configure']: function(){};
$app['twig.configure'] = $app->protect(function($twig) use ($oldTwigConfiguration) {
    $oldTwigConfiguration($twig);
    $twig->addExtension(new Twig_Extensions_Extension_Debug());
});

$app->register(new DoctrineExtension(), array(
    'db.options'    => array(
        'driver'    => $app['db.config.driver'],
        'dbname'    => $app['db.config.dbname'],
        'host'      => $app['db.config.host'],
        'user'      => $app['db.config.user'],
        'password'  => $app['db.config.password'],
    ),
    'db.dbal.class_path'    => __DIR__ . '/../vendor/silex/vendor/doctrine-dbal/lib',
    'db.common.class_path'  => __DIR__ . '/../vendor/silex/vendor/doctrine-common/lib',
));

return $app;
