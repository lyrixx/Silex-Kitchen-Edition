<?php

// Local
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

// Doctrine (DB)
$app['db.options'] = array(
    'driver'   => 'pdo_sqlite',
    'path' => __DIR__.'/../db/database.dat',
);

// User
$app['security.users'] = array('alice' => array('ROLE_USER', 'password'));
