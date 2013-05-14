<?php

ini_set('display_errors', 1);
error_reporting(-1);

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

require __DIR__.'/../resources/config/dev.php';
require __DIR__.'/../src/app.php';
require __DIR__.'/../src/routes.php';
//require __DIR__.'/../src/controllers.php';

$app->run();
