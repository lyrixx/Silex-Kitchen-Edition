<?php

ini_set('display_errors', 1);
date_default_timezone_set('America/Los_Angeles');
use Silex\Application;
require_once __DIR__.'/../vendor/autoload.php';

$app = new Application();

//change to prod.php when not in development:
require __DIR__.'/../resources/config/dev.php';

require __DIR__.'/../src/app.php';
require __DIR__.'/../src/routes.php';
//require __DIR__.'/../src/controllers.php';

$app['http_cache']->run();
//$app->run();