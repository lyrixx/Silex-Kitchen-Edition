<?php

require_once __DIR__ . '/../vendor/silex/autoload.php';

use Silex\Provider\SessionServiceProvider;
use Silex\Provider\SymfonyBridgesServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\DoctrineServiceProvider;

$app = new Silex\Application();

require __DIR__ . '/config.php';

$app->register(new SessionServiceProvider());
$app->register(new SymfonyBridgesServiceProvider());
$app->register(new ValidatorServiceProvider());
$app->register(new FormServiceProvider());
$app->register(new UrlGeneratorServiceProvider());

$app->register(new TranslationServiceProvider(), array(
    'locale_fallback' => $app['locale'],
));
$app['translator.loader'] = new Symfony\Component\Translation\Loader\YamlFileLoader();


$app->register(new TwigServiceProvider(), array(
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

$app->register(new DoctrineServiceProvider(), array(
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
