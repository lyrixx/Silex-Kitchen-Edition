<?php

// Databases
$app['db.config.driver']    = 'pdo_mysql';
$app['db.config.dbname']    = 'silex';
$app['db.config.host']      = '127.0.0.1';
$app['db.config.user']      = 'root';
$app['db.config.password']  = '';

// Debug
$app['debug'] = true;

// Local
$app['locale'] = 'fr';
$app['session.default_locale'] = $app['locale'];
$app['translator.messages'] = array(
    'fr' => __DIR__.'/../Resources/locales/fr.yml',
);

// Cache
$app['cache.path'] = __DIR__ . '/../cache';

// Http cache
$app['http_cache.cache_dir'] = $app['cache.path'] . '/http';

// Assetic
$app['assetic.path_to_cache']       = $app['cache.path'] . DIRECTORY_SEPARATOR . 'assetic' ;
$app['assetic.path_to_web']         = __DIR__ . '/../web/assets';

$app['assetic.input.path_to_assets']    = __DIR__ . '/../Resources/assets';
$app['assetic.input.path_to_css']       = $app['assetic.input.path_to_assets'] . '/css/*.css';
$app['assetic.output.path_to_css']      = '/css/styles.css';
$app['assetic.input.path_to_js']        = array(
    $app['assetic.input.path_to_assets'] . '/js/bootstrap-twipsy.js',
    $app['assetic.input.path_to_assets'] . '/js/bootstrap-*.js',
    $app['assetic.input.path_to_assets'] . '/js/script.js',
);
$app['assetic.output.path_to_js']       = '/js/scripts.js';

$app['assetic.filter.yui_compressor.path'] = '/usr/share/yui-compressor/yui-compressor.jar';
