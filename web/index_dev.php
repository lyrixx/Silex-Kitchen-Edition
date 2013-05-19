<?php

ini_set('display_errors', 1);
error_reporting(-1);

require_once __DIR__.'/../vendor/autoload.php';

use Silex\Provider;

$app = new Silex\Application();

require __DIR__.'/../resources/config/dev.php';
require __DIR__.'/../src/app.php';

$app->register(new Provider\ServiceControllerServiceProvider());
$app->register(new Provider\WebProfilerServiceProvider(), array(
    'profiler.cache_dir' => __DIR__.'/../resources/cache/profiler',
    'profiler.mount_prefix' => '/_profiler', // this is the default
));

require __DIR__.'/../src/controllers.php';

$app->run();
