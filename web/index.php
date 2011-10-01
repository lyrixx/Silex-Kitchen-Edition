<?php

// Autoload
require_once __DIR__.'/../src/autoload.php';

// Silex
$app = require __DIR__.'/../src/app.php';
require __DIR__.'/../src/controllers.php';

if ($app['debug']) {
    return $app->run();
}

$app['http_cache']->run();
