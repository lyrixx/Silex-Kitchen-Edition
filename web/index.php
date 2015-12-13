<?php

require_once __DIR__.'/../vendor/autoload.php';

$app = new SKE\Application(__DIR__.'/../resources/config/prod.php');
$app['http_cache']->run();
