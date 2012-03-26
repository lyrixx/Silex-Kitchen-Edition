<?php

$app = require __DIR__.'/../src/app.php';

require __DIR__.'/../src/controllers.php';

if ($app['debug']) {
    return $app->run();
}

$app['http_cache']->run();
