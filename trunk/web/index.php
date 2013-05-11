<?php

ini_set('display_errors', 1);

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

require __DIR__.'/../resources/config/prod.php';
require __DIR__.'/../src/app.php';
require __DIR__.'/../src/routes.php';
require __DIR__.'/../src/controllers.php';

$app['http_cache']->run();
