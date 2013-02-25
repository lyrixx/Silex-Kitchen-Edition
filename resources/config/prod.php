<?php

// Locale
$app['locale'] = 'fr';
$app['session.default_locale'] = $app['locale'];
$app['translator.messages'] = array(
    'fr' => __DIR__.'/../resources/locales/fr.yml',
);

// Cache
$app['cache.path'] = __DIR__ . '/../cache';

// Http cache
$app['http_cache.cache_dir'] = $app['cache.path'] . '/http';

// Twig cache
$app['twig.options.cache'] = $app['cache.path'] . '/twig';

// Assetic
$app['assetic.enabled'] = true;
$app['assetic.path_to_cache']       = $app['cache.path'] . '/assetic' ;
$app['assetic.path_to_web']         = __DIR__ . '/../../web/assets';
$app['assetic.input.path_to_assets']    = __DIR__ . '/../assets/';
$app['assetic.input.path_to_css']       = $app['assetic.input.path_to_assets'] . 'css/*.css';
$app['assetic.output.path_to_css']      = 'css/styles.css';
$app['assetic.input.path_to_js']        = array(
    $app['assetic.input.path_to_assets'] . 'js/bootstrap.min.js',
    $app['assetic.input.path_to_assets'] . 'js/script.js',
);
$app['assetic.output.path_to_js']       = 'js/scripts.js';
$app['assetic.filter.yui_compressor.path'] = '/usr/share/yui-compressor/yui-compressor.jar';

// Doctrine (db)
$app['db.options'] = array(
    'driver'    => 'pdo_sqlite',
    'path'      => __DIR__ . '/../db/prod.db',
/*
    'driver'   => 'pdo_mysql',
    'host'     => 'localhost',
    'dbname'   => 'phplive',
    'user'     => 'root',
    'password' => '',
*/
);

// Security
$app['security.firewalls'] = array(
    'login' => array('pattern' => '^/login$','anonymous'=>true), // Example of an url available as anonymous user
    //'infos' => array('pattern' => '^/phpinfo$','anonymous' => true),
    //'encode' => array('pattern' => '^/encode$','anonymous' => true),
    'default' => array(
        'pattern' => '^.*$',
        //'anonymous' => true,
        'form' => array('login_path' => '/login', 'check_path' => 'login_check'),
        'logout' => array('logout_path' => '/logout'), // url to call for logging out
        'users' => $app->share(function() use ($app) {
            return new Oclane\UserProvider($app['db']);
        }),
    ),
);

$app['security.access_rules'] = array(
    // You can rename ROLE_USER as you wish
    array('^/login$', ''), // This url is available as anonymous user
    //array('^/phpinfo$',''),
    //array('^/encode$',''),
    array('^/.+$', 'ROLE_USER'),
);

$app['security.role_hierarchy'] = array(
    'ROLE_ADMIN' => array('ROLE_USER', 'ROLE_ALLOWED_TO_SWITCH'),
);
